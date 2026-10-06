<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pendaftar\UpdateStatusRequest;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Auth;

/** Verifikasi berkas: daftar pengajuan masuk (+ pencarian nama) dan ubah status. */
class VerifikasiAdminController extends Controller
{
    public function index(PencarianRequest $request)
    {
        $pendaftars = PendaftaranMagang::latest()
            ->untukAdmin(Auth::user())
            ->sudahDikirim()
            ->cariNama($request->kata())
            ->paginate(10)
            ->withQueryString();

        return view('admin.verifikasi.index', compact('pendaftars'));
    }

    public function update(UpdateStatusRequest $request)
    {
        $request->pendaftaran()->update($request->dataStatus());

        return back()->with('success', 'Status verifikasi berkas berhasil diperbarui!');
    }
}