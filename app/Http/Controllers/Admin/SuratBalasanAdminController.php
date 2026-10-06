<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pendaftar\CetakSuratBalasanRequest;
use App\Services\SuratBalasanGenerator;

/** Cetak surat balasan magang (unduh .docx dari template). */
class SuratBalasanAdminController extends Controller
{
    public function __invoke(CetakSuratBalasanRequest $request, SuratBalasanGenerator $generator)
    {
        $path = $generator->buat($request->pendaftaran());

        if (! $path) {
            return back()->with('error', 'File template surat tidak ditemukan di folder storage/templates/');
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }
}