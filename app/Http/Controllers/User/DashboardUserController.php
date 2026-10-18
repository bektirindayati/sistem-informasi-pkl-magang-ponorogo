<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use Illuminate\Support\Facades\Auth;

/** Dashboard pengguna:pendaftaran terakhir dan instansi yang tersedia.*/
class DashboardUserController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        return view('user.dashboard', [
            'user'        => $user,
            'pendaftaran' => $user->pendaftarans()->latest('id')->first(),
            'dinases'     => Dinas::aktif()->get(),
        ]);
    }
}