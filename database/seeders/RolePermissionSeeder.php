<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar Permission
        $permissions = [
            'dashboard.lihat',
            'transaksi.lihat',
            'transaksi.tambah',
            'transaksi.ubah',
            'transaksi.hapus',
            'kategori.kelola',
            'laporan.lihat',
            'laporan.ekspor',
            'pengguna.kelola',
            'pengaturan.kelola',
            'log.lihat',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role Admin / Pemilik: semua akses
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($permissions);

        // Role Staf Keuangan: input & edit transaksi, lihat & ekspor laporan
        $stafRole = Role::firstOrCreate(['name' => 'Staf Keuangan']);
        $stafRole->syncPermissions([
            'dashboard.lihat',
            'transaksi.lihat',
            'transaksi.tambah',
            'transaksi.ubah',
            'laporan.lihat',
            'laporan.ekspor',
        ]);

        // Role Viewer: hanya lihat dashboard & laporan
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);
        $viewerRole->syncPermissions([
            'dashboard.lihat',
            'transaksi.lihat',
            'laporan.lihat',
            'laporan.ekspor',
        ]);
    }
}

