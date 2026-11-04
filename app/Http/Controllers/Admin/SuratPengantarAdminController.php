<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pendaftar\AdminPendaftaranRequest;
use Illuminate\Http\RedirectResponse;

/** Admin mengoreksi identitas surat pengantar (dipakai mengisi draf surat balasan). */
class SuratPengantarAdminController extends Controller
{
    public function update(AdminPendaftaranRequest $request): RedirectResponse
    {
        $data = $request->validate([
            'nomor_surat_pengantar'   => ['required', 'string', 'max:100'],
            'tanggal_surat_pengantar' => ['required', 'date'],
            'jabatan_penerima'        => ['sometimes', 'required', 'string', 'max:255'],
            'kota_tujuan'             => ['sometimes', 'required', 'string', 'max:100'],
        ], [
            'nomor_surat_pengantar.required'   => 'Nomor surat pengantar wajib diisi.',
            'tanggal_surat_pengantar.required' => 'Tanggal surat pengantar wajib diisi.',
            'tanggal_surat_pengantar.date'     => 'Tanggal surat pengantar tidak valid.',
            'jabatan_penerima.required'        => 'Jabatan penerima surat wajib diisi.',
            'kota_tujuan.required'             => 'Kota kampus / sekolah wajib diisi.',
        ]);

        $request->pendaftaran()->update($data);

        return back()->with('success', 'Data surat pengantar berhasil disimpan.');
    }
}