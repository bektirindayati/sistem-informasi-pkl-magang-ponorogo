<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\BuatPratinjauTemplate;
use App\Models\TemplateSurat;
use App\Services\Surat\ContohSurat;
use App\Services\Surat\PemeriksaTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/** Template surat: super admin mengelola template bawaan, admin dinas mengelola template dinasnya. */
class TemplateSuratController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $dinasId = $this->dinasId($request);

        abort_if(! $user->isSuperAdmin() && ! $dinasId, 403, 'Akun admin ini belum terhubung ke dinas.');

        return view('admin.template.index', [
            'slot'       => TemplateSurat::SLOT,
            'milik'      => TemplateSurat::where('dinas_id', $dinasId)->get()->groupBy('jenis'),
            'bawaan'     => TemplateSurat::whereNull('dinas_id')->aktif()->get()->keyBy('jenis'),
            'superAdmin' => $user->isSuperAdmin(),
            'dinas'      => $user->dinas,
            'kode'       => config('kode_template'),
        ]);
    }

    /** Unduh file template sistem (resources/templates) sebagai bahan awal. */
    public function sistem(string $jenis)
    {
        abort_unless(isset(TemplateSurat::SLOT[$jenis]), 404);

        $path = (string) config('surat.template.' . TemplateSurat::SLOT[$jenis]['config']);
        abort_unless(is_file($path), 404, 'File template sistem tidak ditemukan.');

        return response()->download($path, $this->namaFile($jenis));
    }

    public function unggah(Request $request, string $jenis, PemeriksaTemplate $pemeriksa): RedirectResponse
    {
        abort_unless(isset(TemplateSurat::SLOT[$jenis]), 404);

        $dinasId = $this->dinasId($request);
        abort_if($request->user()->isSuperAdmin() === false && ! $dinasId, 403);

        try {
            $request->validate([
                'template' => ['required', 'file', 'max:5120', 'mimes:docx,zip'],
            ], [
                'template.required' => 'Pilih file template (.docx) terlebih dahulu.',
                'template.max'      => 'Ukuran template maksimal 5 MB.',
                'template.mimes'    => 'Template harus berformat .docx.',
            ]);

            $berkas = $request->file('template');

            if (strtolower($berkas->getClientOriginalExtension()) !== 'docx') {
                throw ValidationException::withMessages(['template' => 'Template harus berformat .docx.']);
            }

            $hasil = $pemeriksa->periksa($berkas, $jenis);
        } catch (ValidationException $e) {
            throw $e->errorBag($jenis);   // pesan tampil pada kartu jenis yang bersangkutan
        }

        $drafLama = TemplateSurat::draf()->where('dinas_id', $dinasId)->where('jenis', $jenis)->get();

        $template = TemplateSurat::create([
            'dinas_id'      => $dinasId,
            'jenis'         => $jenis,
            'status'        => 'draf',
            'file'          => $berkas->store('templates/' . ($dinasId ?? 'bawaan'), 'local'),
            'nama_asli'     => Str::limit($berkas->getClientOriginalName(), 200, ''),
            'kode'          => $hasil['kode'],
            'peringatan'    => $hasil['peringatan'],
            'diunggah_oleh' => $request->user()->id,
        ]);

        $drafLama->each->delete();
        BuatPratinjauTemplate::dispatch($template);

        return back()->with('success', 'Template diunggah. Periksa contoh hasilnya, lalu klik Aktifkan agar dipakai untuk draf surat.');
    }

    public function aktifkan(Request $request, TemplateSurat $template): RedirectResponse
    {
        $this->kelola($request, $template);
        abort_unless($template->status === 'draf', 404);

        $lama = TemplateSurat::aktif()->where('dinas_id', $template->dinas_id)->where('jenis', $template->jenis)->get();

        DB::transaction(function () use ($template, $lama) {
            $lama->each->delete();
            $template->update(['status' => 'aktif', 'diaktifkan_at' => now()]);
        });

        return back()->with('success', 'Template diaktifkan. Draf surat berikutnya mengikuti template ini.');
    }

    /** Menghapus draf, atau template aktif (kembali ke template bawaan / file sistem). */
    public function hapus(Request $request, TemplateSurat $template): RedirectResponse
    {
        $this->kelola($request, $template);

        $aktif = $template->status === 'aktif';
        $template->delete();

        return back()->with('success', $aktif
            ? 'Template dihapus. Draf surat kembali memakai template bawaan.'
            : 'Template yang belum aktif dihapus.');
    }

    public function unduh(Request $request, TemplateSurat $template)
    {
        $this->lihat($request, $template);

        return Storage::disk('local')->download($template->file, $this->namaFile($template->jenis));
    }

    public function pratinjau(Request $request, TemplateSurat $template)
    {
        $this->lihat($request, $template);
        abort_unless($template->pratinjau && Storage::disk('local')->exists($template->pratinjau), 404);

        $respons = Storage::disk('local')->response($template->pratinjau, 'contoh.pdf', ['Content-Type' => 'application/pdf']);
        $respons->headers->set('Cache-Control', 'private, no-cache');

        return $respons;
    }

    public function buatPratinjau(Request $request, TemplateSurat $template): RedirectResponse
    {
        $this->kelola($request, $template);

        $template->update(['pratinjau_status' => 'menunggu', 'pratinjau_pesan' => null]);
        BuatPratinjauTemplate::dispatch($template);

        return back()->with('success', 'Contoh hasil sedang diperbarui.');
    }

    /** Contoh hasil dalam bentuk DOCX (cadangan bila pratinjau PDF tidak tersedia). Memakai profil dinas pengguna. */
    public function contoh(Request $request, TemplateSurat $template, ContohSurat $contoh)
    {
        $this->lihat($request, $template);

        $docx = $contoh->docx($template->jenis, Storage::disk('local')->path($template->file), $request->user()->dinas);

        return response()
            ->download($docx, 'Contoh_' . $this->namaFile($template->jenis))
            ->deleteFileAfterSend(true);
    }

    private function dinasId(Request $request): ?int
    {
        return $request->user()->isSuperAdmin() ? null : $request->user()->dinas_id;
    }

    /** Hanya pemilik (super admin: bawaan; admin dinas: dinasnya) yang boleh mengubah. */
    private function kelola(Request $request, TemplateSurat $template): void
    {
        abort_unless((int) $template->dinas_id === (int) $this->dinasId($request), 404);
    }

    /** Pemilik, atau template bawaan yang aktif (boleh diunduh dan dilihat admin dinas). */
    private function lihat(Request $request, TemplateSurat $template): void
    {
        abort_unless(
            (int) $template->dinas_id === (int) $this->dinasId($request)
                || ($template->dinas_id === null && $template->status === 'aktif'),
            404
        );
    }

    private function namaFile(string $jenis): string
    {
        return 'Template_' . Str::slug(TemplateSurat::SLOT[$jenis]['judul'], '_') . '.docx';
    }
    /** Membuat contoh hasil langsung, tanpa menunggu antrean (bila queue:work tidak berjalan). */
public function buatPratinjauSekarang(Request $request, TemplateSurat $template): RedirectResponse
{
    $this->kelola($request, $template);

    set_time_limit(180);
    $template->update(['pratinjau_status' => 'menunggu', 'pratinjau_pesan' => null]);
    BuatPratinjauTemplate::dispatchSync($template);

    $hasil = $template->fresh();

    return $hasil->pratinjau_status === 'siap'
        ? back()->with('success', 'Contoh hasil berhasil dibuat.')
        : back()->with('error', 'Contoh hasil gagal dibuat: ' . $hasil->pratinjau_pesan);
}
}