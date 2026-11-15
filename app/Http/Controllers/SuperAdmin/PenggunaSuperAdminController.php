<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pengguna\UpdatePenggunaRequest;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/** Kelola pengguna & role (permission kelola-admin, khusus super admin). */
class PenggunaSuperAdminController extends Controller
{
    /**
     * Halaman Kelola Admin: Super Admin + Admin Dinas (tanpa pagination).
     * View: resources/views/admin/pengguna/admin.blade.php
     */
    public function index(PencarianRequest $request)
    {
        $kata = $request->kata();

        $dasar = User::with('dinas')
            ->when($kata, fn ($q) => $q->where(function ($q) use ($kata) {
                $q->where('name', 'like', "%{$kata}%")
                  ->orWhere('email', 'like', "%{$kata}%");
            }));

        $superAdmins = (clone $dasar)
            ->where('role', 'super_admin')
            ->orderBy('name')
            ->get();

        $adminDinas = (clone $dasar)
            ->where('role', 'admin')
            ->orderBy('name')
            ->get();

        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.admin', compact('superAdmins', 'adminDinas', 'dinasList'));
    }

    /**
     * Halaman Kelola Pengguna: User / Pendaftar (dengan pagination).
     * View: resources/views/admin/pengguna/user.blade.php
     */
    public function user(PencarianRequest $request)
    {
        $kata = $request->kata();

        $pengguna = User::with('dinas')
            ->where(fn ($q) => $q->whereNull('role')->orWhere('role', 'user'))
            ->when($kata, fn ($q) => $q->where(function ($q) use ($kata) {
                $q->where('name', 'like', "%{$kata}%")
                  ->orWhere('email', 'like', "%{$kata}%");
            }))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.user', compact('pengguna', 'dinasList'));
    }

    public function update(UpdatePenggunaRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Super Admin bersifat permanen: role-nya tidak boleh diubah.
        if ($user->role === 'super_admin') {
            return back()->withErrors('Role Super Admin bersifat permanen dan tidak dapat diubah.');
        }

        $data = $request->dataPengguna();

        DB::transaction(function () use ($user, $data) {
            $user->update($data);
            $user->syncRoles([$data['role']]); // sinkron ke Spatie
        });

        return back()->with('success', "Role {$user->name} berhasil diperbarui.");
    }
}