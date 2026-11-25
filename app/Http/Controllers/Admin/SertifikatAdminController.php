<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\TanggapiStatusDokumen;
use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranNotifier;
use App\Services\Surat\SertifikatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Admin: draf Word/ZIP, lihat PDF, dan status sertifikat (hingga 7 PDF per pendaftaran). */
class SertifikatAdminController extends Controller
{
    use TanggapiStatusDokumen;

    public function __construct(private SertifikatService $sertifikat)
    {
    }

    private function cari(Request $request, int $id): PendaftaranMagang
    {
        return PendaftaranMagang::with('sertifikatFiles')
            ->untukAdmin($request->user())
            ->sudahDikirim()
            ->findOrFail($id);
    }

    public function draf(Request $request, int $id)
    {
        try {
            return $this->sertifikat->draf($this->cari($request, $id));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, int $id)
    {
        return $this->sertifikat->berkasSertifikat(
            $this->cari($request, $id),
            $request->integer('berkas') ?: null,
            $request->boolean('unduh'),
            untukAdmin: true
        );
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
    'status_sertifikat' => ['required', Rule::in(['diproses', 'diterbitkan'])],
    'sertifikat'        => ['nullable', 'array'],
    'sertifikat.*'      => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
    'hapus'             => ['nullable', 'array'],
    'hapus.*'           => ['string', 'max:20'],
], [
    'sertifikat.*.mimes' => 'Setiap berkas harus berformat PDF.',
    'sertifikat.*.max'   => 'Ukuran tiap PDF maksimal 5 MB.',
    'sertifikat.*.file'  => 'Berkas sertifikat tidak valid.',
]);

$this->sertifikat->simpanSertifikat(
    $pendaftaran,
    $data['status_sertifikat'],
    $request->file('sertifikat', []),
    $data['hapus'] ?? []
);

        return $this->tanggapan(
            $data['status_sertifikat'],
            'Sertifikat'
        );
    }

    public function email(
        Request $request,
        int $id,
        PendaftaranNotifier $notifier
    ): RedirectResponse {
        return $this->kirimEmail(
            $this->cari($request, $id),
            $notifier,
            'sertifikat',
            'sertifikat'
        );
    }
}
