<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. USER
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'bektirindayati@gmail.com'],
            [
                'name' => 'Bekti Rindayati',
                'password' => Hash::make('password'),
                'role' => 'user',
                'dinas_id' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. DINAS KOMINFO
        |--------------------------------------------------------------------------
        */
        $dinasKominfo = Dinas::updateOrCreate(
            [
                'nama_dinas' => 'Dinas Komunikasi, Informasi dan Statistik',
            ],
            [
                'deskripsi' => 'Dinas Komunikasi, Informasi dan Statistik Kabupaten Ponorogo',
                'status' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 3. ADMIN DINAS KOMINFO
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => '24050974005@mhs.unesa.ac.id'],
            [
                'name' => 'BEKTI RINDAYATI',
                'password' => Hash::make('password'),
                'role' => 'admin',

                // Kolom lama, dipertahankan agar kompatibel
                // dengan kode yang masih menggunakan $user->dinas

                // Relasi utama admin dengan tabel dinases
                'dinas_id' => $dinasKominfo->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 4. SUPER ADMIN
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'rindayatibekti@gmail.com'],
            [
                'name' => 'BEKTI RINDAYATI',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'dinas_id' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 5. TIGA BIDANG DINAS KOMINFO
        |--------------------------------------------------------------------------
        */

        // Bidang E-Government
        InstansiBidang::updateOrCreate(
            [
                'dinas_id' => $dinasKominfo->id,
                'nama_bidang' => 'Bidang E-Government',
            ],
            [
                'deskripsi' => 'Aplikasi, Jaringan & SPBE',
            ]
        );

        // Bidang Informasi dan Komunikasi Publik
        InstansiBidang::updateOrCreate(
            [
                'dinas_id' => $dinasKominfo->id,
                'nama_bidang' => 'Bidang Informasi dan Komunikasi Publik',
            ],
            [
                'deskripsi' => 'IKP & Media',
            ]
        );

        // Bidang Statistik dan Persandian
        InstansiBidang::updateOrCreate(
            [
                'dinas_id' => $dinasKominfo->id,
                'nama_bidang' => 'Bidang Statistik dan Persandian',
            ],
            [
                'deskripsi' => 'Statistik dan Persandian',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 6. ROLE & PERMISSION
        |--------------------------------------------------------------------------
        */
        $this->call(RolePermissionSeeder::class);
    }
}