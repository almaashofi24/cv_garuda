<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin / Pemilik
        $admin = User::firstOrCreate(
            ['email' => 'admin@cvgaruda.com'],
            [
                'name' => 'H. Sudirman, S.E. (Admin/Pemilik)',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['Admin']);

        // 2. Akun Staf Keuangan
        $staf = User::firstOrCreate(
            ['email' => 'keuangan@cvgaruda.com'],
            [
                'name' => 'Siti Aminah, A.Md. (Staf Keuangan)',
                'password' => Hash::make('password'),
            ]
        );
        $staf->syncRoles(['Staf Keuangan']);

        // 3. Akun Viewer
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@cvgaruda.com'],
            [
                'name' => 'Budi Santoso (Viewer)',
                'password' => Hash::make('password'),
            ]
        );
        $viewer->syncRoles(['Viewer']);
    }
}

