<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pendaftaran\BasePendaftaranRequest;
use App\Http\Requests\Pendaftaran\DestroyPendaftaranRequest;
use App\Http\Requests\Pendaftaran\DraftPendaftaranRequest;
use App\Http\Requests\Pendaftaran\EditPendaftaranRequest;
use App\Http\Requests\Pendaftaran\StorePendaftaranRequest;
use App\Http\Requests\Pendaftaran\UpdatePendaftaranRequest;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Seluruh alur pendaftaran milik pengguna: form, draft, kirim, status, riwayat, ubah, dan batal. */
class PendaftaranUserController extends Controller
{
    // ---------- Form & penyimpanan ----------

    public function create(Request $request)
    {
        $user = $request->user();

 // Profil akademik minimal harus lengkap sebelum melakukan pendaftaran.
if ($kurang = $user->profilKurang()) {
    return redirect()
        ->route('user.profil')
        ->with(
            'error',
            'Lengkapi dulu data profil berikut sebelum mendaftar magang: '
                . implode(', ', $kurang)
                . '.'
        );
}

        $terakhir = $user->pendaftaranTerakhir;

        if ($pesan = $terakhir?->pesanTerkunci()) {
            $tujuan = $terakhir->status === 'revisi'
                ? route('user.pendaftaran.edit', $terakhir->id)
                : route('user.pendaftaran.status');

            return redirect($tujuan)->with('error', $pesan);
        }

        return view('user.pendaftaran.create', [
            'pendaftaran' => $terakhir?->status === 'draft' ? $terakhir : null,
            'dinases'     => Dinas::aktif()->get(),
        ]);
    }

    public function saveDraft(DraftPendaftaranRequest $request)
    {
        $pendaftaran = $this->simpan($request->pendaftaran(), $request);

        return response()->json([
            'success'   => true,
            'id'        => $pendaftaran->id,
            'has_surat' => filled($pendaftaran->surat_pengantar),
            'message'   => 'Draft berhasil disimpan.',
        ]);
    }

    public function store(StorePendaftaranRequest $request)
    {
        $this->simpan($request->pendaftaran(), $request);

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dikirim dan sedang menunggu verifikasi.');
    }

    public function edit(EditPendaftaranRequest $request)
    {
        return view('user.pendaftaran.create', [
            'pendaftaran' => $request->pendaftaran(),
            'dinases'     => Dinas::aktif()->get(),
        ]);
    }

    public function update(UpdatePendaftaranRequest $request)
    {
        $this->simpan($request->pendaftaran(), $request);

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Perubahan berhasil disimpan, menunggu verifikasi ulang.');
    }

    // ---------- Status & riwayat ----------

    public function status(Request $request)
    {
        $user = $request->user();

        return view('user.pendaftaran.status', [
            'pendaftaran' => $user->pendaftaranTerakhir,
            'nomorUrut'   => $user->pendaftarans()->count() ?: null,
        ]);
    }

    public function riwayat(Request $request)
    {
        return view('user.pendaftaran.riwayat', [
            'riwayat' => $request->user()->pendaftarans()->latest('id')->get(),
        ]);
    }

    // ---------- Hapus / batal ----------

    /** Hapus draft atau batalkan pengajuan. Berkas ikut terhapus lewat hook model. */
    public function destroy(DestroyPendaftaranRequest $request)
    {
        $pendaftaran = $request->pendaftaran();
        $draft = $pendaftaran->status === 'draft';

        $pendaftaran->delete();

        return redirect()
            ->route($draft ? 'user.dashboard' : 'user.pendaftaran.status')
            ->with('success', $draft ? 'Draft berhasil dihapus.' : 'Pendaftaran berhasil dibatalkan.');
    }

    // ---------- Data untuk form ----------

    /** Bidang milik satu dinas (dipanggil JavaScript saat dinas dipilih). */
    public function getBidang(int $dinasId)
    {
        return InstansiBidang::where('dinas_id', $dinasId)
            ->orderBy('nama_bidang')
            ->get(['id', 'nama_bidang', 'deskripsi']);
    }

    // ---------- Pembantu ----------

    /** Isi data dari request, simpan berkas, lalu simpan record. */
    private function simpan(PendaftaranMagang $pendaftaran, BasePendaftaranRequest $request): PendaftaranMagang
{
    $pendaftaran->fill($request->dataPendaftaran());

    $baru = [];
    foreach (PendaftaranMagang::KOLOM_BERKAS as $kolom) {
        if ($request->hasFile($kolom)) {
            $baru[] = $pendaftaran->{$kolom} = $request->file($kolom)
                ->store("pendaftaran/{$kolom}", PendaftaranMagang::DISK_BERKAS);
        }
    }

    try {
        // Berkas lama dihapus otomatis oleh hook `updated` di model.
        $pendaftaran->save();
    } catch (\Throwable $e) {
        // Gagal simpan: berkas yang baru diunggah jangan jadi sampah.
        Storage::disk(PendaftaranMagang::DISK_BERKAS)->delete($baru);

        throw $e;
    }

    return $pendaftaran;
}
}