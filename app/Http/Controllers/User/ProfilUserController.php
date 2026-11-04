<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profil\UpdateProfilRequest;
use Illuminate\Http\Request;

/** Profil akademik pengguna (dipakai auto-fill form pendaftaran). */
class ProfilUserController extends Controller
{
    public function index(Request $request)
    {
        return view('user.profil', ['user' => $request->user()]);
    }

    public function update(UpdateProfilRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}