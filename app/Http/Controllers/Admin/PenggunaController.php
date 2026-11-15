<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Halaman Kelola Admin: Super Admin + Admin Dinas (tanpa pagination).
     * View: resources/views/admin/pengguna/admin.blade.php
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $dasar = User::with('dinas')
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")));

        $superAdmins = (clone $dasar)
            ->where('role', 'super_admin')
            ->orderBy('name')
            ->get();

        $adminDinas = (clone $dasar)
            ->where('role', 'admin')
            ->orderBy('name')
            ->get();

        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.admin', compact(
            'superAdmins',
            'adminDinas',
            'dinasList'
        ));
    }

    /**
     * Halaman Kelola Pengguna: User / Pendaftar (dengan pagination).
     * View: resources/views/admin/pengguna/user.blade.php
     */
    public function user(Request $request)
    {
        $search = $request->search;

        $pengguna = User::with('dinas')
            ->where(fn ($q) => $q->whereNull('role')->orWhere('role', 'user'))
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.user', compact('pengguna', 'dinasList'));
    }

    /**
     * Mengubah role dan dinas pengguna.
     *
     * Aturan:
     * - Super Admin     : permanen, tidak dapat diubah.
     * - Admin Dinas     : hanya Admin Dinas atau Super Admin.
     * - User            : hanya User Biasa atau Admin Dinas.
     */
    public function update(Request $request, User $user)
    {
        $roleSaatIni = $user->role ?: 'user';

        if ($roleSaatIni === 'super_admin') {
            return back()->withErrors(
                'Role Super Admin bersifat permanen dan tidak dapat diubah.'
            );
        }

        $roleDiizinkan = match ($roleSaatIni) {
            'admin' => ['admin', 'super_admin'],
            default => ['user', 'admin'],
        };

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
        ], [
            'role.in'                => 'Perubahan role tersebut tidak diizinkan.',
            'dinas_id.required_if'   => 'Dinas wajib dipilih untuk Admin Dinas.',
        ]);

        // Cegah pengguna mengubah role akunnya sendiri.
        if ($user->id === auth()->id() && $data['role'] !== $roleSaatIni) {
            return back()->withErrors(
                'Anda tidak dapat mengubah role akun Anda sendiri.'
            );
        }

        $user->update([
            'role'     => $data['role'],
            'dinas_id' => $data['role'] === 'admin' ? $data['dinas_id'] : null,
        ]);

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    /**
     * Memblokir atau membuka blokir akun pendaftar.
     * Hanya berlaku untuk akun dengan role user.
     */
    public function updateStatusAkun(Request $request, User $user)
    {
        abort_unless(
            in_array($user->role, ['user', null], true),
            403,
            'Status akun admin tidak dapat diubah melalui fitur ini.'
        );

        $data = $request->validate([
            'status_akun' => [
                'required',
                Rule::in([
                    User::STATUS_AKTIF,
                    User::STATUS_DIBLOKIR,
                ]),
            ],
            'alasan_blokir' => [
                'nullable',
                'required_if:status_akun,diblokir',
                'string',
                'max:1000',
            ],
        ], [
            'status_akun.required'      => 'Status akun wajib dipilih.',
            'status_akun.in'            => 'Status akun tidak valid.',
            'alasan_blokir.required_if' => 'Alasan pemblokiran wajib diisi.',
            'alasan_blokir.max'         => 'Alasan pemblokiran maksimal 1000 karakter.',
        ]);

        if ($data['status_akun'] === User::STATUS_DIBLOKIR) {
            $user->status_akun   = User::STATUS_DIBLOKIR;
            $user->alasan_blokir = $data['alasan_blokir'];
            $user->blocked_at    = now();
            $user->blocked_by    = auth()->id();
        } else {
            $user->status_akun   = User::STATUS_AKTIF;
            $user->alasan_blokir = null;
            $user->blocked_at    = null;
            $user->blocked_by    = null;
        }

        $user->save();

        return back()->with(
            'success',
            $data['status_akun'] === User::STATUS_DIBLOKIR
                ? 'Akun pendaftar berhasil diblokir.'
                : 'Blokir akun pendaftar berhasil dibuka.'
        );
    }
}