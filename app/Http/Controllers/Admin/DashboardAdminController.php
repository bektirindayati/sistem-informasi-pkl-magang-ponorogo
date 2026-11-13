<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaPendaftaran;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;

/** Dashboard admin: ringkasan pengajuan sesuai dinas admin (super admin: semua dinas). */
class DashboardAdminController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $dasar = PendaftaranMagang::untukAdmin($user)->sudahDikirim();

        $perStatus = (clone $dasar)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalPengajuan = $perStatus->sum();

        return view('admin.dashboardadmin', [
            'totalPengajuan' => $totalPengajuan,

            'totalPeserta' => $totalPengajuan + AnggotaPendaftaran::whereHas(
                'pendaftaran',
                fn ($q) => $q->untukAdmin($user)->sudahDikirim()
            )->count(),

            'totalDiterima' => $perStatus['diterima'] ?? 0,

            'totalPending' => ($perStatus['pending'] ?? 0)
                + ($perStatus['revisi'] ?? 0),

            'pendaftarTerbaru' => (clone $dasar)
                ->withCount('anggotas')
                ->latest()
                ->take(5)
                ->get(),

            'suratMenunggu' => (clone $dasar)
                ->where('status', 'diterima')
                ->where(fn ($q) => $q
                    ->whereNull('status_surat')
                    ->orWhere('status_surat', '!=', 'diterbitkan'))
                ->count(),

            'perluSelesai' => (clone $dasar)
                ->where('status', 'diterima')
                ->whereDate('tanggal_selesai', '<', today())
                ->count(),
        ]);
    }
}
