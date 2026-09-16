<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // USER
        User::updateOrCreate(
            ['email' => 'bektirindayati@gmail.com'],
            [
                'name' => 'Bekti Rindayati',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // ADMIN
        User::updateOrCreate(
            ['email' => '24050974005@mhs.unesa.ac.id'],
            [
                'name' => 'BEKTI RINDAYATI',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // SUPER ADMIN
        User::updateOrCreate(
            ['email' => 'rindayatibekti@gmail.com'],
            [
                'name' => 'Bekti Rindayati',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // ROLE & PERMISSION
        $this->call(RolePermissionSeeder::class);
    }
}