<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Concerns\KelolaBerkasPendaftaran;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pendaftaran\DestroyDraftRequest;
use App\Http\Requests\Pendaftaran\DestroyPendaftaranRequest;
use App\Http\Requests\Pendaftaran\DraftPendaftaranRequest;
use App\Http\Requests\Pendaftaran\EditPendaftaranRequest;
use App\Http\Requests\Pendaftaran\StorePendaftaranRequest;
use App\Http\Requests\Pendaftaran\UpdatePendaftaranRequest;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Auth;

/** Seluruh alur pendaftaran milik pengguna: form, draft, kirim, status, riwayat, ubah, batal. */
class PendaftaranUserController extends Controller
{
    use KelolaBerkasPendaftaran;

    // ---------- Form & penyimpanan ----------

    public function create()
{
    $user = Auth::user();

    // Profil akademik minimal harus lengkap sebelum melakukan pendaftaran.
    if (
        empty($user->nim_nisn) ||
        empty($user->instansi) ||
        empty($user->jurusan)
    ) {
        return redirect()
            ->route('user.profil')
            ->with(
                'error',
                'Lengkapi data akademik (NIM/NISN, instansi, dan jurusan) pada profil kamu terlebih dahulu sebelum mendaftar magang.'
            );
    }

    $terakhir = PendaftaranMagang::where('user_id', $user->id)
        ->latest('id')
        ->first();

    // Data profil digunakan sebagai nilai awal/default
    // untuk formulir pendaftaran baru.
    $profil = [
        'nama_lengkap' => $user->nama_lengkap ?: $user->name,
        'nim_nisn'     => $user->nim_nisn,
        'instansi'     => $user->instansi,
        'jurusan'      => $user->jurusan,
        'no_hp'        => $user->no_hp,
        'alamat'       => $user->alamat,
        'kabupaten'    => $user->kabupaten,
        'provinsi'     => $user->provinsi,
    ];

    return match ($terakhir?->status) {
        'pending' => redirect()
            ->route('user.pendaftaran.status')
            ->with('error', 'Pendaftaran kamu masih dalam proses verifikasi.'),

        'diterima' => redirect()
            ->route('user.pendaftaran.status')
            ->with('error', 'Pendaftaran kamu sudah diterima dan belum dapat membuat pendaftaran baru.'),

        'revisi' => redirect()
            ->route('user.pendaftaran.edit', $terakhir->id)
            ->with('error', 'Pendaftaran kamu perlu direvisi terlebih dahulu sebelum mengirim ulang.'),

        default => view('user.pendaftaran.create', [
            'pendaftaran' => $terakhir?->status === 'draft'
                ? $terakhir
                : null,

            'profil' => $profil,

            'dinases' => Dinas::aktif()->get(),
        ]),
    };
}

    public function saveDraft(DraftPendaftaranRequest $request)
    {
        $pendaftaran = $this->simpan($request->pendaftaran(), $request);

        return response()->json([
            'success'   => true,
            'id'        => $pendaftaran->id,
            'has_surat' => ! empty($pendaftaran->surat_pengantar),
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

    public function status()
    {
        $userId = Auth::id();

        $pendaftaran = PendaftaranMagang::where('user_id', $userId)->latest('id')->first();

        $nomorUrut = $pendaftaran
            ? PendaftaranMagang::where('user_id', $userId)->count()
            : null;

        return view('user.pendaftaran.status', compact('pendaftaran', 'nomorUrut'));
    }

    public function riwayat()
    {
        $riwayat = PendaftaranMagang::where('user_id', Auth::id())
            ->latest('id')
            ->get();

        return view('user.pendaftaran.riwayat', compact('riwayat'));
    }

    // ---------- Hapus / batal ----------

    public function destroyDraft(DestroyDraftRequest $request)
    {
        $this->hapusPendaftaran($request->pendaftaran());

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Draft berhasil dihapus.');
    }

    public function destroy(DestroyPendaftaranRequest $request)
    {
        $this->hapusPendaftaran($request->pendaftaran());

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    // ---------- Data untuk form ----------

    /** Bidang milik satu dinas (dipanggil JavaScript saat dinas dipilih). */
    public function getBidang($dinasId)
    {
        return response()->json(
            InstansiBidang::where('dinas_id', $dinasId)
                ->orderBy('nama_bidang')
                ->get(['id', 'nama_bidang', 'deskripsi'])
        );
    }

    // ---------- Pembantu ----------

    /** Isi data dari request, simpan berkas, lalu simpan record (draft / kirim / ubah). */
    private function simpan(PendaftaranMagang $pendaftaran, $request): PendaftaranMagang
    {
        $pendaftaran->fill($request->dataPendaftaran());
        $this->simpanBerkas($pendaftaran, $request);
        $pendaftaran->save();

        return $pendaftaran;
    }
}