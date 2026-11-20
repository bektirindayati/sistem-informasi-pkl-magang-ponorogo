<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;

/** Dashboard admin: tiga daftar kerja (super admin: semua dinas, admin dinas: dinasnya). */
class DashboardAdminController extends Controller
{
    public function __invoke(Request $request)
    {
        $user  = $request->user();
        $dasar = fn () => PendaftaranMagang::untukAdmin($user);

        return view('admin.dashboardadmin', [
            'lingkup'    => $user->isSuperAdmin() ? 'Seluruh Dinas' : ($user->dinas?->nama_dinas ?? 'Dinas'),
            'menunggu'   => $dasar()->menungguReview()->count(),
            'surat'      => $dasar()->suratBelumTerbit()->count(),
            'sertifikat' => $dasar()->sertifikatBelumTerbit()->count(),
            'pendaftarTerbaru' => $dasar()->sudahDikirim()->withCount('anggotas')->latest()->take(5)->get(),
        ]);
    }
}