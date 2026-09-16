<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * hak akses tidak lagi cuma berdasarkan nama role, tapi berdasarkan PERMISSION granular yang dipasang ke tiap role.
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan cache permission Spatie supaya perubahan langsung kepakai
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Daftar permission granular
        $permissions = [
            'lihat-dashboard',
            'lihat-pendaftar',
            'verifikasi-pendaftar',
            'kelola-instansi',
            'kelola-dokumentasi',
            'kelola-dinas',
            'kelola-admin',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 2. Role utama (tetap 3 seperti sebelumnya)
        $roleUser = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // 3. permission ke tiap role //
        $roleAdmin->syncPermissions([
            'lihat-dashboard',
            'lihat-pendaftar',
            'verifikasi-pendaftar',
            'kelola-instansi',
            'kelola-dokumentasi',
        ]);

        $roleSuperAdmin->syncPermissions($permissions); // semua permission


        // 4. Sinkronisasi role lama dari kolom users.role ke Spatie.
        User::whereNotNull('role')
            ->get()
            ->each(function (User $user) {
                if (in_array($user->role, ['user', 'admin', 'super_admin'])) {
                    $user->syncRoles([$user->role]);
                }
            });
    }
}