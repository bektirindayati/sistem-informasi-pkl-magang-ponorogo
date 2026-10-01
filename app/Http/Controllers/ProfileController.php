<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        return view('user.profil', ['user' => Auth::user()]);
    }

    // Menyimpan perubahan profil
    public function update(UpdateProfilRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}