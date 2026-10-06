<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Auth;

/** Dashboard pengguna: pendaftaran terakhir dan instansi yang tersedia. */
class DashboardUserController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        return view('user.dashboard', [
            'user'        => $user,
            'pendaftaran' => PendaftaranMagang::where('user_id', $user->id)->latest('id')->first(),
            'dinases'     => Dinas::aktif()->get(),
        ]);
    }
}