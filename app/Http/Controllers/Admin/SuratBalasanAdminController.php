<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\TanggapiStatusDokumen;
use App\Http\Requests\Admin\SuratBalasan\AksesSuratBalasanRequest;
use App\Http\Requests\Admin\SuratBalasan\UnggahSuratBalasanRequest;
use App\Services\PendaftaranNotifier;
use App\Services\Surat\SuratBalasanService;
use Illuminate\Http\RedirectResponse;

class SuratBalasanAdminController extends Controller
{
    use TanggapiStatusDokumen;

    public function __construct(private SuratBalasanService $surat)
    {
    }

    public function draf(AksesSuratBalasanRequest $request)
    {
        return $this->surat->draf($request->pendaftaran());
    }

    public function show(AksesSuratBalasanRequest $request)
    {
        return $this->surat->berkasFinal(
            $request->pendaftaran(),
            $request->boolean('unduh'),
            untukAdmin: true
        );
    }

    public function status(UnggahSuratBalasanRequest $request): RedirectResponse
    {
        $status = $request->validated('status_surat');

        $this->surat->simpanStatus(
            $request->pendaftaran(),
            $status,
            $request->file('surat_balasan')
        );

        return $this->tanggapan($status, 'Surat balasan');
    }

    public function email(
        AksesSuratBalasanRequest $request,
        PendaftaranNotifier $notifier
    ): RedirectResponse {
        return $this->kirimEmail(
            $request->pendaftaran(),
            $notifier,
            'balasan',
            'surat balasan'
        );
    }
}
