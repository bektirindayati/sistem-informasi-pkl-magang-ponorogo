<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use App\Services\StatistikMagangService;
use Illuminate\Http\Request;

/** Dashboard admin: statistik sesuai lingkup (super admin: semua dinas, admin dinas: dinasnya). */
class DashboardAdminController extends Controller
{
    public function __invoke(Request $request, StatistikMagangService $statistik)
    {
        $user  = $request->user();
        $dasar = PendaftaranMagang::untukAdmin($user)->sudahDikirim();

        return view('admin.dashboardadmin', [
            'statistik' => $statistik->ringkasan($user),
            'lingkup'   => $user->isSuperAdmin() ? 'Seluruh Dinas' : ($user->dinas?->nama_dinas ?? 'Dinas'),

            'menunggu' => (clone $dasar)->whereIn('status', ['pending', 'revisi'])->count(),

            // != 'diterbitkan' membuang baris NULL, jadi NULL dihitung terpisah
            'suratMenunggu' => (clone $dasar)->where('status', 'diterima')
                ->where(fn ($q) => $q->whereNull('status_surat')->orWhere('status_surat', '!=', 'diterbitkan'))
                ->count(),

            'perluSelesai' => (clone $dasar)->where('status', 'diterima')
                ->whereDate('tanggal_selesai', '<', today())->count(),

            'pendaftarTerbaru' => (clone $dasar)->withCount('anggotas')->latest()->take(5)->get(),
        ]);
    }
}
