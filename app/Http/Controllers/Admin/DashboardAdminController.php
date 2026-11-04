<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;

/** Dashboard admin: ringkasan pengajuan sesuai dinas admin (super admin: semua dinas). */
class DashboardAdminController extends Controller
{
    public function __invoke(Request $request)
    {
        $dasar = PendaftaranMagang::untukAdmin($request->user())->sudahDikirim();

        $perStatus = (clone $dasar)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboardadmin', [
            'totalMagang'      => $perStatus->sum(),
            'totalPkl'         => $perStatus['diterima'] ?? 0,
            'totalPending'     => ($perStatus['pending'] ?? 0) + ($perStatus['revisi'] ?? 0),
            'pendaftarTerbaru' => (clone $dasar)->latest()->take(5)->get(),

            // Diterima tapi suratnya belum dikirim: pengingat untuk admin.
            'suratMenunggu' => (clone $dasar)->where('status', 'diterima')->where('status_surat', '!=', 'diterbitkan')->count(),
            // Tanggal selesai sudah lewat tetapi belum ditandai Selesai: saatnya mengurus sertifikat.
            'perluSelesai' => (clone $dasar)->where('status', 'diterima')->whereDate('tanggal_selesai', '<', today())->count(),
        ]);
    }
}