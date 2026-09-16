<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftaran = PendaftaranMagang::where('user_id', $user->id)
            ->latest('id')
            ->first();

        $dinases = Dinas::where('status', true)
            ->orderBy('nama_dinas')
            ->get();

        return view('user.dashboard', compact(
            'user',
            'pendaftaran',
            'dinases'
        ));
    }
}