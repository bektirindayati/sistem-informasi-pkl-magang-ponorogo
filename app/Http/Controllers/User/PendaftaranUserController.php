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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Seluruh alur pendaftaran milik pengguna:
 * form, draft, kirim, detail, status, riwayat, ubah, dan batal.
 */
class PendaftaranUserController extends Controller
{
    // ---------- Form & penyimpanan ----------

    public function create(Request $request)
    {
        $user = $request->user();

        // 1. Periksa apakah akun sedang diblokir.
        if ($user->isBlocked()) {
            return redirect()
                ->route('user.pendaftaran.status')
                ->with(
                    'error',
                    'Akun Anda sedang diblokir oleh administrator. '
                    . 'Anda masih dapat melihat status dan riwayat pendaftaran, '
                    . 'tetapi tidak dapat membuat atau mengubah pengajuan. '
                    . 'Hubungi administrator untuk informasi lebih lanjut.'
                );
        }

        // 2. Profil akademik minimal harus lengkap.
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

        // 3. Ambil pendaftaran terakhir milik pengguna.
        $terakhir = $user->pendaftaranTerakhir;

        // 4. Periksa apakah pengajuan terakhir terkunci.
        if ($pesan = $terakhir?->pesanTerkunci()) {
            $tujuan = $terakhir->status === 'revisi'
                ? route('user.pendaftaran.edit', $terakhir->id)
                : route('user.pendaftaran.status');

            return redirect($tujuan)->with('error', $pesan);
        }

        // 5. Tampilkan formulir pendaftaran.
        return view('user.pendaftaran.create', [
            'pendaftaran' => $terakhir?->status === 'draft'
                ? $terakhir->load('anggotas')
                : null,
            'dinases' => Dinas::aktif()->get(),
        ]);
    }

    /**
     * Menyimpan draft pendaftaran melalui AJAX.
     */
    public function saveDraft(DraftPendaftaranRequest $request)
    {
        $pendaftaran = $this->simpan(
            $request->pendaftaran(),
            $request
        );

        return response()->json([
            'success' => true,
            'id' => $pendaftaran->id,
            'has_surat' => filled($pendaftaran->surat_pengantar),
            'detail_url' => route(
                'user.pendaftaran.show',
                $pendaftaran->id
            ),
            'message' => 'Draft berhasil disimpan.',
        ]);
    }

    /**
     * Mengirim pendaftaran baru.
     */
    public function store(StorePendaftaranRequest $request)
    {
        $this->simpan(
            $request->pendaftaran(),
            $request
        );

        return redirect()
            ->route('user.pendaftaran.riwayat')
            ->with(
                'success',
                'Pendaftaran berhasil dikirim dan sedang menunggu verifikasi.'
            );
    }

    /**
     * Menampilkan kembali formulir untuk mengedit pengajuan.
     */
    public function edit(EditPendaftaranRequest $request)
    {
        return view('user.pendaftaran.create', [
            'pendaftaran' => $request->pendaftaran()->load('anggotas'),
            'dinases' => Dinas::aktif()->get(),
        ]);
    }

    /**
     * Menyimpan perubahan pengajuan.
     */
    public function update(UpdatePendaftaranRequest $request)
    {
        $pendaftaran = $this->simpan(
            $request->pendaftaran(),
            $request
        );

        return redirect()
            ->route('user.pendaftaran.show', $pendaftaran->id)
            ->with(
                'success',
                'Perubahan berhasil disimpan, menunggu verifikasi ulang.'
            );
    }

    // ---------- Detail pengajuan ----------

    /**
     * Menampilkan detail pendaftaran milik pengguna.
     */
    public function show(Request $request, int $id)
    {
        $pendaftaran = $request->user()
            ->pendaftarans()
            ->with(['dinas', 'bidang', 'anggotas'])
            ->findOrFail($id);

        return view('user.pendaftaran.show', compact('pendaftaran'));
    }

    // ---------- Status & riwayat ----------

    public function status(Request $request)
{
    $user = $request->user();

    return view('user.pendaftaran.status', [
        'pendaftaran' => $user->pendaftaranTerakhir?->load('anggotas'),
        'nomorUrut' => $user->pendaftarans()->count() ?: null,
    ]);
}

    public function riwayat(Request $request)
    {
        return view('user.pendaftaran.riwayat', [
            'riwayat' => $request->user()
                ->pendaftarans()
                ->withCount('anggotas')
                ->latest('id')
                ->get(),
        ]);
    }

    // ---------- Hapus / batal ----------

    /**
     * Hapus draft atau batalkan pengajuan.
     * Berkas ikut terhapus melalui hook model.
     */
    public function destroy(DestroyPendaftaranRequest $request)
    {
        $pendaftaran = $request->pendaftaran();
        $draft = $pendaftaran->status === 'draft';

        $pendaftaran->delete();

        return redirect()
            ->route(
                $draft
                    ? 'user.dashboard'
                    : 'user.pendaftaran.status'
            )
            ->with(
                'success',
                $draft
                    ? 'Draft berhasil dihapus.'
                    : 'Pendaftaran berhasil dibatalkan.'
            );
    }

    // ---------- Data untuk form ----------

    /**
     * Mengambil bidang milik satu dinas.
     * Dipanggil JavaScript saat dinas dipilih.
     */
    public function getBidang(int $dinasId)
    {
        return InstansiBidang::where('dinas_id', $dinasId)
            ->orderBy('nama_bidang')
            ->get(['id', 'nama_bidang', 'deskripsi']);
    }

    // ---------- Pembantu ----------

    /**
     * Mengisi data dari request, menyimpan berkas,
     * lalu menyimpan record pendaftaran dan anggota kelompok.
     */
    private function simpan(
        PendaftaranMagang $pendaftaran,
        BasePendaftaranRequest $request
    ): PendaftaranMagang {

        $pendaftaran->fill($request->dataPendaftaran());

        $baru = [];

        foreach (PendaftaranMagang::KOLOM_BERKAS as $kolom) {
            if ($request->hasFile($kolom)) {
                $baru[] = $pendaftaran->{$kolom} =
                    $request->file($kolom)->store(
                        "pendaftaran/{$kolom}",
                        PendaftaranMagang::DISK_BERKAS
                    );
            }
        }

        try {
            DB::transaction(function () use ($pendaftaran, $request) {
                // Simpan data utama pendaftaran.
                $pendaftaran->save();

                // Hapus anggota lama.
                $pendaftaran->anggotas()->delete();

                // Simpan anggota terbaru.
                $pendaftaran->anggotas()->createMany(
                    $request->dataAnggota()
                );
            });
        } catch (\Throwable $e) {
            // Jika gagal simpan, hapus berkas baru agar tidak menjadi sampah.
            Storage::disk(PendaftaranMagang::DISK_BERKAS)
                ->delete($baru);

            throw $e;
        }

        return $pendaftaran;
    }
}
