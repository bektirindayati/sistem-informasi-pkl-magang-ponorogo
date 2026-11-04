<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dokumentasi\StoreDokumentasiRequest;
use App\Http\Requests\Admin\Dokumentasi\UpdateDokumentasiRequest;
use App\Models\Dinas;
use App\Models\DokumentasiMagang;
use App\Services\DokumentasiService;

/**
 * Kelola dokumentasi kegiatan (foto + uraian).
 * Super admin melihat/mengelola semua; admin dinas hanya milik dinasnya.
 * Di halaman publik, semua dokumentasi tetap tampil.
 */
class DokumentasiAdminController extends Controller
{
    /** Daftar dokumentasi + form tambah baru. */
    public function index()
    {
        return view('admin.dokumentasi.index', [
            'dokumentasis' => $this->daftar(),
            'editItem'     => null,
            'dinasList'    => $this->daftarDinas(),
        ]);
    }

    public function store(StoreDokumentasiRequest $request, DokumentasiService $dokumentasi)
    {
        $dokumentasi->buat($request->dataKegiatan(), $request->fotoBaru());

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil ditambahkan dan langsung tampil di halaman utama.');
    }

    /** View yang sama dengan index(), form berubah ke mode edit. */
    public function edit($id)
    {
        return view('admin.dokumentasi.index', [
            'dokumentasis' => $this->daftar(),
            'editItem'     => $this->cari($id),
            'dinasList'    => $this->daftarDinas(),
        ]);
    }

    public function update(UpdateDokumentasiRequest $request, DokumentasiService $dokumentasi)
    {
        $dokumentasi->ubah(
            $request->dokumentasi(),
            $request->dataKegiatan(),
            $request->fotoBaru(),
            $request->hapusFotoIds(),
            $request->uraianLama()
        );

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy($id, DokumentasiService $dokumentasi)
    {
        $dokumentasi->hapus($this->cari($id));

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    /** Hanya yang boleh dikelola user ini; milik dinas lain = 404. */
    private function cari($id): DokumentasiMagang
    {
        return DokumentasiMagang::with('fotos')
            ->bisaDikelolaOleh(auth()->user())
            ->findOrFail($id);
    }

    private function daftar()
    {
        return DokumentasiMagang::with(['fotos', 'dinas'])
            ->bisaDikelolaOleh(auth()->user())
            ->terbaru()
            ->paginate(10)
            ->withQueryString();
    }

    /** Pilihan dinas di form: hanya untuk super admin. */
    private function daftarDinas()
    {
        return auth()->user()->isSuperAdmin()
            ? Dinas::orderBy('nama_dinas')->get()
            : collect();
    }
}