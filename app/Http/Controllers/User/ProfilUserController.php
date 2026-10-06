<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profil\UpdateProfilRequest;
use Illuminate\Support\Facades\Auth;

/** Profil akademik pengguna (dipakai auto-fill form pendaftaran). */
class ProfilUserController extends Controller
{
    public function index()
    {
        return view('user.profil', [
            'user' => Auth::user(),
        ]);
    }

    public function update(UpdateProfilRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}