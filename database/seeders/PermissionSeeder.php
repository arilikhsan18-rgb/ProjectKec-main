<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Definisikan Permissions
        Permission::create(['name' => 'input data']);
        Permission::create(['name' => 'view own rw reports']);
        Permission::create(['name' => 'view own kelurahan reports']);
        Permission::create(['name' => 'view all reports']);
        Permission::create(['name' => 'edit all reports']);
        Permission::create(['name' => 'manage users']); // Contoh permission lain

        // Definisikan Roles dan berikan permission yang sesuai
        $rtRole = Role::findByName('RT');
        $rtRole->givePermissionTo('input data');

        $rwRole = Role::findByName('RW');
        $rwRole->givePermissionTo('view own rw reports');

        $kelurahanRole = Role::findByName('KELURAHAN');
        $kelurahanRole->givePermissionTo('view own kelurahan reports');

        // SUPERADMIN dan KECAMATAN bisa melakukan segalanya
        $superAdminRole = Role::findByName('SUPERADMIN');
        // tidak perlu givePermissionTo, kita akan handle via Gate::before

        $kecamatanRole = Role::findByName('KECAMATAN');
        // tidak perlu givePermissionTo, kita akan handle via Gate::before
    }
}