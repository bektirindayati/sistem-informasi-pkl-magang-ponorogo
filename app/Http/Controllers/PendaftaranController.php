<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\StorePendaftaranRequest;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    // Status yang masih boleh diedit
    private const STATUS_BOLEH_EDIT = ['draft', 'revisi'];

    // Status yang masih boleh dihapus/dibatalkan
    private const STATUS_BOLEH_HAPUS = ['draft', 'pending', 'revisi', 'ditolak'];

    // Form pendaftaran
public function create()
{
    $userId = Auth::id();
    $user = Auth::user();

    // Profil harus lengkap dulu (NIM/NISN, instansi, jurusan) sebelum
    // bisa membuka form pendaftaran -- data ini dipakai untuk auto-fill
    // form, jadi kalau kosong user akan mengetik ulang dari nol.
    if (empty($user->nim_nisn) || empty($user->instansi) || empty($user->jurusan)) {
        return redirect()
            ->route('user.profil')
            ->with('error', 'Lengkapi data akademik (NIM/NISN, instansi, dan jurusan) pada profil kamu terlebih dahulu sebelum mendaftar magang.');
    }

    $dinases = Dinas::where('status', true)
        ->orderBy('nama_dinas')
        ->get();

    $pendaftaranTerakhir = PendaftaranMagang::where('user_id', $userId)
        ->latest('id')
        ->first();

    if ($pendaftaranTerakhir && $pendaftaranTerakhir->status === 'pending') {
        return redirect()
            ->route('user.pendaftaran.status')
            ->with('error', 'Pendaftaran kamu masih dalam proses verifikasi.');
    }

    if ($pendaftaranTerakhir && $pendaftaranTerakhir->status === 'diterima') {
        return redirect()
            ->route('user.pendaftaran.status')
            ->with('error', 'Pendaftaran kamu sudah diterima dan belum dapat membuat pendaftaran baru.');
    }

    if ($pendaftaranTerakhir && $pendaftaranTerakhir->status === 'revisi') {
        return redirect()
            ->route('user.pendaftaran.edit', $pendaftaranTerakhir->id)
            ->with('error', 'Pendaftaran kamu perlu direvisi terlebih dahulu sebelum mengirim ulang.');
    }

    if ($pendaftaranTerakhir && $pendaftaranTerakhir->status === 'draft') {
        return view('user.pendaftaran.create', [
            'pendaftaran' => $pendaftaranTerakhir,
            'dinases' => $dinases,
        ]);
    }

    return view('user.pendaftaran.create', [
        'pendaftaran' => null,
        'dinases' => $dinases,
    ]);
}

    // Simpan draft
    public function saveDraft(StorePendaftaranRequest $request)
    {
        $userId = Auth::id();

        $pendaftaranTerakhir = PendaftaranMagang::where('user_id', $userId)
            ->latest('id')
            ->first();

        if ($pendaftaranTerakhir && in_array($pendaftaranTerakhir->status, ['draft', 'revisi'])) {
            // Pakai record yang sudah ada; jika sebelumnya revisi,
            // sekarang disimpan ulang sebagai draft.
            $pendaftaran = $pendaftaranTerakhir;
            $pendaftaran->status = 'draft';
        } elseif ($pendaftaranTerakhir && $pendaftaranTerakhir->status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran kamu masih dalam proses verifikasi.',
            ], 403);
        } elseif ($pendaftaranTerakhir && $pendaftaranTerakhir->status === 'diterima') {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran kamu sudah diterima dan belum dapat membuat pengajuan baru.',
            ], 403);
        } else {
            $pendaftaran = new PendaftaranMagang();
            $pendaftaran->user_id = $userId;
            $pendaftaran->status = 'draft';
        }

        $pendaftaran->dinas_id = $request->input('dinas_id');
        $pendaftaran->instansi_bidang_id = $request->input('instansi_bidang_id');
        $pendaftaran->kategori = $request->input('kategori');
        $pendaftaran->nama_lengkap = $request->input('nama_lengkap');
        $pendaftaran->nim_nisn = $request->input('nim_nisn');
        $pendaftaran->instansi = $request->input('instansi');
        $pendaftaran->jurusan = $request->input('jurusan');
        $pendaftaran->no_hp = $request->input('no_hp');
        $pendaftaran->alamat = $request->input('alamat');
        $pendaftaran->kabupaten = $request->input('kabupaten');
        $pendaftaran->provinsi = $request->input('provinsi');
        $pendaftaran->tanggal_mulai = $request->input('tanggal_mulai');
        $pendaftaran->tanggal_selesai = $request->input('tanggal_selesai');

        if ($request->filled('dinas_id')) {
            $dinas = Dinas::find($request->input('dinas_id'));
            if ($dinas) {
                $pendaftaran->dinas_tujuan = $dinas->nama_dinas;
            }
        }

        if ($request->filled('instansi_bidang_id')) {
            $bidang = InstansiBidang::where('id', $request->input('instansi_bidang_id'))
                ->where('dinas_id', $request->input('dinas_id'))
                ->first();

            if ($bidang) {
                $pendaftaran->divisi = $bidang->nama_bidang;
            }
        }

        if ($request->hasFile('surat_pengantar')) {
            if ($pendaftaran->surat_pengantar) {
                Storage::disk('public')->delete($pendaftaran->surat_pengantar);
            }
            $pendaftaran->surat_pengantar = $this->uploadBerkas(
                $request->file('surat_pengantar'),
                'surat_pengantar'
            );
        }

        if ($request->hasFile('proposal')) {
            if ($pendaftaran->proposal) {
                Storage::disk('public')->delete($pendaftaran->proposal);
            }
            $pendaftaran->proposal = $this->uploadBerkas(
                $request->file('proposal'),
                'proposal'
            );
        }

        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'id' => $pendaftaran->id,
            'message' => 'Draft berhasil disimpan.',
        ]);
    }

    // Hapus draft
    public function destroyDraft($id)
    {
        $userId = Auth::id();

        $pendaftaran = PendaftaranMagang::where('id', $id)
            ->where('user_id', $userId)
            ->where('status', 'draft')
            ->firstOrFail();

        if ($pendaftaran->surat_pengantar) {
            Storage::disk('public')->delete($pendaftaran->surat_pengantar);
        }

        if ($pendaftaran->proposal) {
            Storage::disk('public')->delete($pendaftaran->proposal);
        }

        $pendaftaran->delete();

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Draft berhasil dihapus.');
    }

    // Kirim pendaftaran (pendaftaran baru, bukan lanjutan draft)
    public function store(StorePendaftaranRequest $request)
    {
        $userId = Auth::id();

        $validatedData = $request->validated();

        $dinas = Dinas::findOrFail($validatedData['dinas_id']);

        $bidang = InstansiBidang::where('id', $validatedData['instansi_bidang_id'])
            ->where('dinas_id', $validatedData['dinas_id'])
            ->firstOrFail();

        $validatedData['dinas_tujuan'] = $dinas->nama_dinas;
        $validatedData['divisi'] = $bidang->nama_bidang;

        if ($request->hasFile('surat_pengantar')) {
            $validatedData['surat_pengantar'] = $this->uploadBerkas(
                $request->file('surat_pengantar'),
                'surat_pengantar'
            );
        }

        if ($request->hasFile('proposal')) {
            $validatedData['proposal'] = $this->uploadBerkas(
                $request->file('proposal'),
                'proposal'
            );
        }

        $validatedData['status'] = 'pending';
        $validatedData['alasan_penolakan'] = null;
        $validatedData['alasan_revisi'] = null;
        $validatedData['user_id'] = $userId;

        PendaftaranMagang::create($validatedData);

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dikirim dan sedang menunggu verifikasi.');
    }

    // Cek status
    public function status()
    {
        $pendaftaran = PendaftaranMagang::where('user_id', Auth::id())
            ->latest('id')
            ->first();

        $nomorUrut = $pendaftaran
            ? PendaftaranMagang::where('user_id', Auth::id())
                ->where('id', '<=', $pendaftaran->id)
                ->count()
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

    // Edit pendaftaran
    public function edit($id)
    {
        $pendaftaran = PendaftaranMagang::findOrFail($id);

        abort_if($pendaftaran->user_id !== Auth::id(), 403);

        abort_if(
            !in_array($pendaftaran->status, self::STATUS_BOLEH_EDIT),
            403,
            'Pendaftaran yang sudah dikirim tidak dapat diubah lagi.'
        );

        $dinases = Dinas::where('status', true)
            ->orderBy('nama_dinas')
            ->get();

        return view('user.pendaftaran.create', [
            'pendaftaran' => $pendaftaran,
            'dinases' => $dinases,
        ]);
    }

    // Update pendaftaran
    public function update(StorePendaftaranRequest $request, $id)
    {
        $pendaftaran = PendaftaranMagang::findOrFail($id);

        abort_if($pendaftaran->user_id !== Auth::id(), 403);

        abort_if(
            !in_array($pendaftaran->status, self::STATUS_BOLEH_EDIT),
            403,
            'Pendaftaran yang sudah dikirim tidak dapat diubah lagi.'
        );

        $validatedData = $request->validated();

        $dinas = Dinas::findOrFail($validatedData['dinas_id']);

        $bidang = InstansiBidang::where('id', $validatedData['instansi_bidang_id'])
            ->where('dinas_id', $validatedData['dinas_id'])
            ->firstOrFail();

        $validatedData['dinas_tujuan'] = $dinas->nama_dinas;
        $validatedData['divisi'] = $bidang->nama_bidang;

        if ($request->hasFile('surat_pengantar')) {
            if ($pendaftaran->surat_pengantar) {
                Storage::disk('public')->delete($pendaftaran->surat_pengantar);
            }
            $validatedData['surat_pengantar'] = $this->uploadBerkas(
                $request->file('surat_pengantar'),
                'surat_pengantar'
            );
        }

        if ($request->hasFile('proposal')) {
            if ($pendaftaran->proposal) {
                Storage::disk('public')->delete($pendaftaran->proposal);
            }
            $validatedData['proposal'] = $this->uploadBerkas(
                $request->file('proposal'),
                'proposal'
            );
        }

        $validatedData['status'] = 'pending';
        $validatedData['alasan_penolakan'] = null;
        $validatedData['alasan_revisi'] = null;

        $pendaftaran->update($validatedData);

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Perubahan berhasil disimpan, menunggu verifikasi ulang.');
    }

    // Hapus atau batalkan pengajuan
    public function destroy($id)
    {
        $pendaftaran = PendaftaranMagang::findOrFail($id);

        abort_if($pendaftaran->user_id !== Auth::id(), 403);

        abort_if(
            !in_array($pendaftaran->status, self::STATUS_BOLEH_HAPUS),
            403,
            'Pendaftaran yang sudah diterima/selesai tidak dapat dibatalkan.'
        );

        if ($pendaftaran->surat_pengantar) {
            Storage::disk('public')->delete($pendaftaran->surat_pengantar);
        }

        if ($pendaftaran->proposal) {
            Storage::disk('public')->delete($pendaftaran->proposal);
        }

        $pendaftaran->delete();

        return redirect()
            ->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    // Upload berkas
    private function uploadBerkas(UploadedFile $file, string $folder): string
{
    $filename = time().'_'.$file->getClientOriginalName();

    $file->storeAs($folder, $filename, 'public');

    return "{$folder}/{$filename}";
}

    // Ambil bidang berdasarkan dinas
    public function getBidang($dinasId)
    {
        $bidangs = InstansiBidang::where('dinas_id', $dinasId)
            ->orderBy('nama_bidang')
            ->get(['id', 'nama_bidang', 'deskripsi']);

        return response()->json($bidangs);
    }
}