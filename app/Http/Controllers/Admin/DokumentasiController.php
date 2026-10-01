<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDokumentasiRequest;
use App\Http\Requests\UpdateDokumentasiRequest;
use App\Models\DokumentasiMagang;
use App\Services\DokumentasiService;

class DokumentasiController extends Controller
{
    /** Daftar dokumentasi + form tambah baru. */
    public function index()
    {
        return view('admin.dokumentasi.index', [
            'dokumentasis' => $this->daftar(),
            'editItem' => null,
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
            'editItem' => DokumentasiMagang::with('fotos')->findOrFail($id),
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
        $dokumentasi->hapus(DokumentasiMagang::findOrFail($id));

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    private function daftar()
    {
        return DokumentasiMagang::with('fotos')->latest()->get();
    }
}