<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // PENTING: Gunakan model Role dari Spatie

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar nama-nama role
        $roles = [
            'SUPERADMIN',
            'KECAMATAN',
            'KELURAHAN',
            'RW',
            'RT',
        ];

        // Buat setiap role
        foreach ($roles as $roleName) {
            // Menggunakan firstOrCreate agar seeder bisa dijalankan berulang kali tanpa error
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }
}
