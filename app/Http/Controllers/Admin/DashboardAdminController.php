<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Auth;

/** Dashboard admin: ringkasan pengajuan sesuai dinas admin (super admin: semua dinas). */
class DashboardAdminController extends Controller
{
    public function __invoke()
    {
        $dasar = PendaftaranMagang::untukAdmin(Auth::user())->sudahDikirim();

        $perStatus = (clone $dasar)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboardadmin', [
            'totalMagang'      => $perStatus->sum(),
            'totalPkl'         => $perStatus['diterima'] ?? 0,
            'totalPending'     => ($perStatus['pending'] ?? 0) + ($perStatus['revisi'] ?? 0),
            'pendaftarTerbaru' => (clone $dasar)->latest()->take(5)->get(),
        ]);
    }
}