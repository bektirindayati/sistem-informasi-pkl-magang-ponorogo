<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use App\Services\Surat\SuratSelesaiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Admin: draf Word, lihat PDF, dan status surat selesai magang. */
class SuratSelesaiAdminController extends Controller
{
    public function __construct(private SuratSelesaiService $surat)
    {
    }

    private function cari(Request $request, int $id): PendaftaranMagang
    {
        return PendaftaranMagang::untukAdmin($request->user())->sudahDikirim()->findOrFail($id);
    }

    public function draf(Request $request, int $id)
    {
        try {
            return $this->surat->draf($this->cari($request, $id));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());   // mis. template belum disiapkan
        }
    }

    public function show(Request $request, int $id)
    {
        return $this->surat->berkasFinal($this->cari($request, $id), $request->boolean('unduh'), untukAdmin: true);
    }

    public function status(Request $request, int $id): RedirectResponse
    {
        $pendaftaran = $this->cari($request, $id);

        abort_unless(
            in_array($pendaftaran->status, ['diterima', 'selesai'], true),
            403,
            'Dokumen hanya untuk pendaftaran yang sudah diterima.'
        );

        $data = $request->validate([
            'status_surat_selesai' => ['required', Rule::in(['diproses', 'diterbitkan'])],
            'surat_selesai'        => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'surat_selesai.mimes' => 'Berkas harus berformat PDF.',
            'surat_selesai.max'   => 'Ukuran PDF maksimal 5 MB.',
        ]);

        $this->surat->simpanStatus(
            $pendaftaran,
            $data['status_surat_selesai'],
            $request->file('surat_selesai')
        );

        return back()->with('success', $data['status_surat_selesai'] === 'diterbitkan'
            ? 'Surat selesai magang berhasil diterbitkan.'
            : 'Status surat disimpan: sedang diproses.');
    }
}