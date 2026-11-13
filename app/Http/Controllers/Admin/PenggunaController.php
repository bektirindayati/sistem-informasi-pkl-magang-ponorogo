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

        // 1. Super admin: selalu tampil paling atas, tanpa pagination.
        $superAdmins = (clone $dasar)
            ->where('role', 'super_admin')
            ->orderBy('name')
            ->get();

        // 2. Admin dinas: tanpa pagination.
        $adminDinas = (clone $dasar)
            ->where('role', 'admin')
            ->orderBy('name')
            ->get();

        // 3. User / pendaftar: menggunakan pagination.
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

    /**
     * Mengubah role dan dinas pengguna.
     */
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

    /**
     * Memblokir atau membuka blokir akun pendaftar.
     * Hanya berlaku untuk akun dengan role user.
     */
    public function updateStatusAkun(Request $request, User $user)
    {
        // Jangan izinkan pemblokiran akun admin atau super admin.
        abort_unless(
            in_array($user->role, ['user', null], true),
            403,
            'Status akun admin tidak dapat diubah melalui fitur ini.'
        );

        // Validasi status akun dan alasan pemblokiran.
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
            'status_akun.required' => 'Status akun wajib dipilih.',
            'status_akun.in' => 'Status akun tidak valid.',
            'alasan_blokir.required_if' => 'Alasan pemblokiran wajib diisi.',
            'alasan_blokir.max' => 'Alasan pemblokiran maksimal 1000 karakter.',
        ]);

        if ($data['status_akun'] === User::STATUS_DIBLOKIR) {
            $user->status_akun = User::STATUS_DIBLOKIR;
            $user->alasan_blokir = $data['alasan_blokir'];
            $user->blocked_at = now();
            $user->blocked_by = auth()->id();
        } else {
            // Membuka blokir dan membersihkan informasi pemblokiran.
            $user->status_akun = User::STATUS_AKTIF;
            $user->alasan_blokir = null;
            $user->blocked_at = null;
            $user->blocked_by = null;
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
