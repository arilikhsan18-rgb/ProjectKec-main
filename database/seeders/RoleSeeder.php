<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel roles terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Gunakan nama peran dalam HURUF BESAR
        Role::create(['name' => 'SUPERADMIN']);
        Role::create(['name' => 'KECAMATAN']);
        Role::create(['name' => 'KELURAHAN']);
        Role::create(['name' => 'RW']);
        Role::create(['name' => 'RT']);
    }
}

