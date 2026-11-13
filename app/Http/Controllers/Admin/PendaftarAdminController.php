<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pendaftar\AdminPendaftaranRequest;
use App\Models\PendaftaranMagang;

/**
 * Data pendaftar: daftar per kelompok (+ pencarian nama)
 * dan halaman detail.
 */
class PendaftarAdminController extends Controller
{
    public function index(PencarianRequest $request)
{
    $admin  = $request->user();
    $search = $request->kata();

    $dasar = fn () => PendaftaranMagang::withCount('anggotas')
        ->latest()
        ->untukAdmin($admin)
        ->cariNama($search);

    return view('admin.pendaftar.index', [
        'pemagangAktif'   => $dasar()->aktif()->get(),
        'pemagangSelesai' => $dasar()->selesai()->get(),
        'pemagangDitolak' => $dasar()->ditolak()->get(),
        'search'          => $search,
    ]);
}

    public function show(AdminPendaftaranRequest $request)
{
    return view('admin.pendaftar.show', [
        'pendaftaran' => $request->pendaftaran()->load([
            'dinas',
            'bidang',
            'anggotas',
        ]),
    ]);
}
}