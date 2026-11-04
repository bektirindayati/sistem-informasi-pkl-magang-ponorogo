<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $dasar = User::with('dinas')
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")));

        // 1. Super admin: selalu tampil paling atas, tanpa pagination
        $superAdmins = (clone $dasar)
            ->where('role', 'super_admin')
            ->orderBy('name')
            ->get();

        // 2. Admin dinas: tanpa pagination
        $adminDinas = (clone $dasar)
            ->where('role', 'admin')
            ->orderBy('name')
            ->get();

        // 3. User / pendaftar: menggunakan pagination
        $pengguna = (clone $dasar)
            ->where(fn ($q) => $q->whereNull('role')->orWhere('role', 'user'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.index', compact(
            'superAdmins',
            'adminDinas',
            'pengguna',
            'dinasList'
        ));
    }

    public function update(Request $request, User $user)
    {
        // Tentukan role yang boleh dipilih berdasarkan role saat ini.
        $roleSaatIni = $user->role ?: 'user';

        $roleDiizinkan = match ($roleSaatIni) {
            'super_admin' => ['super_admin'],
            'admin'       => ['admin', 'user'],
            default       => ['user', 'admin', 'super_admin'],
        };

        // Validasi request berdasarkan aturan role.
        $data = $request->validate([
            'role' => [
                'required',
                Rule::in($roleDiizinkan),
            ],
            'dinas_id' => [
                'nullable',
                'required_if:role,admin',
                'exists:dinas,id',
            ],
        ]);

        // Cegah pengguna mengubah role akunnya sendiri.
        if (
            $user->id === auth()->id()
            && $data['role'] !== $roleSaatIni
        ) {
            return back()->withErrors(
                'Anda tidak dapat mengubah role akun Anda sendiri.'
            );
        }

        // Simpan perubahan role dan dinas.
        $user->update([
            'role' => $data['role'],
            'dinas_id' => $data['role'] === 'admin'
                ? $data['dinas_id']
                : null,
        ]);

        return back()->with(
            'success',
            'Role pengguna berhasil diperbarui.'
        );
    }
}
