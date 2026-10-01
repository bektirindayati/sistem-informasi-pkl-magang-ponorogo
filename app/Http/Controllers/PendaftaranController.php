<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\KelolaBerkasPendaftaran;
use App\Http\Requests\DestroyDraftRequest;
use App\Http\Requests\DestroyPendaftaranRequest;
use App\Http\Requests\DraftPendaftaranRequest;
use App\Http\Requests\EditPendaftaranRequest;
use App\Http\Requests\StorePendaftaranRequest;
use App\Http\Requests\UpdatePendaftaranRequest;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    use KelolaBerkasPendaftaran;

    // Form pendaftaran
    public function create()
    {
        $user = Auth::user();

        // Profil harus lengkap dulu karena dipakai untuk auto-fill form.
        if (empty($user->nim_nisn) || empty($user->instansi) || empty($user->jurusan)) {
            return redirect()
                ->route('user.profil')
                ->with('error', 'Lengkapi data akademik (NIM/NISN, instansi, dan jurusan) pada profil kamu terlebih dahulu sebelum mendaftar magang.');
        }

        $terakhir = PendaftaranMagang::where('user_id', $user->id)->latest('id')->first();

        return match ($terakhir?->status) {
            'pending' => redirect()->route('user.pendaftaran.status')
                ->with('error', 'Pendaftaran kamu masih dalam proses verifikasi.'),

            'diterima' => redirect()->route('user.pendaftaran.status')
                ->with('error', 'Pendaftaran kamu sudah diterima dan belum dapat membuat pendaftaran baru.'),

            'revisi' => redirect()->route('user.pendaftaran.edit', $terakhir->id)
                ->with('error', 'Pendaftaran kamu perlu direvisi terlebih dahulu sebelum mengirim ulang.'),

            default => view('user.pendaftaran.create', [
                'pendaftaran' => $terakhir?->status === 'draft' ? $terakhir : null,
                'dinases'     => $this->dinasAktif(),
            ]),
        };
    }

    // Simpan draft
    public function saveDraft(DraftPendaftaranRequest $request)
    {
        $pendaftaran = $request->pendaftaran();
        $pendaftaran->fill($request->dataPendaftaran());
        $this->simpanBerkas($pendaftaran, $request);
        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'id'      => $pendaftaran->id,
            'has_surat' => ! empty($pendaftaran->surat_pengantar),
            'message' => 'Draft berhasil disimpan.',
        ]);
    }

    // Hapus draft
    public function destroyDraft(DestroyDraftRequest $request)
    {
        $this->hapusPendaftaran($request->pendaftaran());

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Draft berhasil dihapus.');
    }

    // Kirim pendaftaran baru
    public function store(StorePendaftaranRequest $request)
    {
        $pendaftaran = $request->pendaftaran();
        $pendaftaran->fill($request->dataPendaftaran());
        $this->simpanBerkas($pendaftaran, $request);
        $pendaftaran->save();

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dikirim dan sedang menunggu verifikasi.');
    }

    // Cek status
    public function status()
    {
        $userId = Auth::id();

        $pendaftaran = PendaftaranMagang::where('user_id', $userId)->latest('id')->first();

        $nomorUrut = $pendaftaran
            ? PendaftaranMagang::where('user_id', $userId)->count()
            : null;

        return view('user.pendaftaran.status', compact('pendaftaran', 'nomorUrut'));
    }

    // Riwayat semua pendaftaran
    public function riwayat()
    {
        $riwayat = PendaftaranMagang::where('user_id', Auth::id())
            ->latest('id')
            ->get();

        return view('user.pendaftaran.riwayat', compact('riwayat'));
    }

    // Form edit
    public function edit(EditPendaftaranRequest $request)
    {
        return view('user.pendaftaran.create', [
            'pendaftaran' => $request->pendaftaran(),
            'dinases'     => $this->dinasAktif(),
        ]);
    }

    // Update & kirim ulang
    public function update(UpdatePendaftaranRequest $request)
    {
        $pendaftaran = $request->pendaftaran();
        $pendaftaran->fill($request->dataPendaftaran());
        $this->simpanBerkas($pendaftaran, $request);
        $pendaftaran->save();

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Perubahan berhasil disimpan, menunggu verifikasi ulang.');
    }

    // Hapus / batalkan pengajuan
    public function destroy(DestroyPendaftaranRequest $request)
    {
        $this->hapusPendaftaran($request->pendaftaran());

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    // Ambil bidang berdasarkan dinas
    public function getBidang($dinasId)
    {
        return response()->json(
            InstansiBidang::where('dinas_id', $dinasId)
                ->orderBy('nama_bidang')
                ->get(['id', 'nama_bidang', 'deskripsi'])
        );
    }

    private function dinasAktif()
    {
        return Dinas::where('status', true)->orderBy('nama_dinas')->get();
    }
}