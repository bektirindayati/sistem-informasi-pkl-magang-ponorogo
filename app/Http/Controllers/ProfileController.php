<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        $user = Auth::user();

        return view('user.profil', compact('user'));
    }

    // Menyimpan perubahan profil
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nim_nisn' => ['nullable', 'string', 'max:50'],
            'instansi' => ['nullable', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);
        return redirect()
            ->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}