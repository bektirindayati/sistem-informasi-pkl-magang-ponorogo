<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pendaftar\AdminPendaftaranRequest;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;

/**
 * Data pendaftar: daftar per kelompok (+ pencarian nama)
 * dan halaman detail.
 */
class PendaftarAdminController extends Controller
{
    public function index(Request $request)
{
    $admin = $request->user();

    $dasar = fn () => PendaftaranMagang::with('anggotas:id,pendaftaran_magang_id,nama_lengkap,nim_nisn')
        ->latest()
        ->untukAdmin($admin);

    // Daftar kerja dari kartu dashboard. Angka kartu dan isi daftar memakai scope yang sama.
    $filter = [
        'menunggu'   => ['Menunggu Review', 'Pengajuan baru dan revisi yang menunggu keputusan.', 'menungguReview'],
        'surat'      => ['Surat Balasan Belum Terbit', 'Pendaftar yang sudah diterima, tetapi surat balasannya belum diterbitkan.', 'suratBelumTerbit'],
        'sertifikat' => ['Selesai Magang, Sertifikat Belum Terbit', 'Magang sudah berakhir atau ditandai selesai, tetapi sertifikatnya belum diterbitkan.', 'sertifikatBelumTerbit'],
    ][$request->string('tampil')->toString()] ?? null;

    if ($filter) {
        return view('admin.pendaftar.index', [
            'filter'       => ['judul' => $filter[0], 'keterangan' => $filter[1]],
            'daftarFilter' => $dasar()->{$filter[2]}()->get(),
        ]);
    }

    return view('admin.pendaftar.index', [
        'filter'          => null,
        'pemagangAktif'   => $dasar()->aktif()->get(),
        'pemagangSelesai' => $dasar()->selesai()->get(),
        'pemagangDitolak' => $dasar()->ditolak()->get(),
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