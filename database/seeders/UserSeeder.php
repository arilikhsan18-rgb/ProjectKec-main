<?php

namespace Database\Seeders;

use App\Models\User;
// --- PERBAIKAN 1: Gunakan Model Role dari Spatie ---
// Kita tidak lagi menggunakan App\Models\Role, tapi model yang disediakan oleh package.
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        
        // Ambil objek Role dari setiap peran yang sudah ada di database
        // Pastikan Anda sudah menjalankan RoleSeeder terlebih dahulu
        $superAdminRole = Role::where('name', 'SUPERADMIN')->firstOrFail();
        $kecamatanRole  = Role::where('name', 'KECAMATAN')->firstOrFail();
        $kelurahanRole  = Role::where('name', 'KELURAHAN')->firstOrFail();
        $rwRole         = Role::where('name', 'RW')->firstOrFail();
        $rtRole         = Role::where('name', 'RT')->firstOrFail();

        $this->command->info('Memulai pembuatan semua akun pengguna...');

        // --- PERBAIKAN 2: Hapus 'role_id' dan Gunakan assignRole() ---
        // 1. Buat Akun Super Admin
        $superAdminUser = User::create([
            'name'          => 'Super Administrator', // <-- Tetap sama
            'username'      => 'superadmin',
            'email'         => 'admin@simdawani.com',
            'password'      => Hash::make('superadmin123'),
            'status'        => 'active',
            // 'role_id'       => $superAdminRole->id, // <-- BARIS INI DIHAPUS
        ]);
        // Berikan role menggunakan metode dari Spatie
        $superAdminUser->assignRole($superAdminRole);


        // 2. Buat Akun Kecamatan
        $kecamatanUser = User::create([
            'name'          => 'Kecamatan Tawang', // <-- DIUBAH
            'username'      => 'kec_tawang',
            'email'         => 'tawang@gmail.com',
            'password'      => Hash::make('tawanghebat'),
            'status'        => 'active',
            'parent_id'     => null,
            // 'role_id'       => $kecamatanRole->id, // <-- BARIS INI DIHAPUS
        ]);
        $kecamatanUser->assignRole($kecamatanRole);


        // Data struktur wilayah Kecamatan Tawang
        $kelurahanData = [
            ['nama' => 'Kahuripan',   'rw_count' => 16, 'rt_count' => 95],
            ['nama' => 'Cikalang',    'rw_count' => 14, 'rt_count' => 56],
            ['nama' => 'Lengkongsari','rw_count' => 12, 'rt_count' => 71],
            ['nama' => 'Empangsari',  'rw_count' => 11, 'rt_count' => 44],
            ['nama' => 'Tawangsari',  'rw_count' => 11, 'rt_count' => 46],
        ];

        // Looping untuk membuat akun Kelurahan, RW, dan RT secara otomatis
        foreach ($kelurahanData as $data) {
            $namaKelurahanLower = strtolower($data['nama']);

            // 3. Buat Akun Kelurahan
            $kelurahanUser = User::create([
                'name'              => 'Kelurahan ' . $data['nama'], // <-- DIUBAH
                'username'          => 'kel_' . $namaKelurahanLower,
                'email'             => $namaKelurahanLower . '@kelurahan.com',
                'password'          => Hash::make('password'),
                'status'            => 'active',
                'parent_id'         => $kecamatanUser->id,
                'nama_kelurahan'    => $data['nama'],
                // 'role_id'           => $kelurahanRole->id, // <-- DIHAPUS
            ]);
            $kelurahanUser->assignRole($kelurahanRole);

            $rtDihasilkan = 0;
            for ($i = 1; $i <= $data['rw_count']; $i++) {
                $nomorRW = str_pad($i, 2, '0', STR_PAD_LEFT);

                // 4. Buat Akun RW
                $rwUser = User::create([
                    'name'              => 'RW ' . $nomorRW . ' - ' . $data['nama'], // <-- DIUBAH
                    'username'          => 'rw' . $nomorRW . '_' . $namaKelurahanLower,
                    'email'             => 'rw' . $nomorRW . '.' . $namaKelurahanLower . '@rw.com',
                    'password'          => Hash::make('password'),
                    'status'            => 'active',
                    'parent_id'         => $kelurahanUser->id,
                    'nama_kelurahan'    => $data['nama'],
                    'nomor_rw'          => $nomorRW,
                    // 'role_id'           => $rwRole->id, // <-- DIHAPUS
                ]);
                $rwUser->assignRole($rwRole);

                // Logika pembagian RT
                $sisaRW = $data['rw_count'] - $i + 1;
                $sisaRT = $data['rt_count'] - $rtDihasilkan;
                $rtUntukRWIni = ($sisaRW > 0) ? round($sisaRT / $sisaRW) : 0;
                
                for ($j = 1; $j <= $rtUntukRWIni; $j++) {
                    if ($rtDihasilkan >= $data['rt_count']) break;
                    
                    $nomorRT = str_pad($j, 3, '0', STR_PAD_LEFT);
                    
                    // 5. Buat Akun RT
                    $rtUser = User::create([
                        'name'              => 'RT ' . $nomorRT . ' / RW ' . $nomorRW . ' - ' . $data['nama'], // <-- DIUBAH
                        'username'          => 'rt' . $nomorRT . '_rw' . $nomorRW . '_' . $namaKelurahanLower,
                        'email'             => 'rt' . $nomorRT . '.rw' . $nomorRW . '.' . $namaKelurahanLower . '@rt.com',
                        'password'          => Hash::make('password'),
                        'status'            => 'active',
                        'parent_id'         => $rwUser->id,
                        'nama_kelurahan'    => $data['nama'],
                        'nomor_rw'          => $nomorRW,
                        'nomor_rt'          => $nomorRT,
                        // 'role_id'           => $rtRole->id, // <-- DIHAPUS
                    ]);
                    $rtUser->assignRole($rtRole);
                }
                $rtDihasilkan += $rtUntukRWIni;
            }
        }
        
        $totalUsers = User::count();
        $this->command->info("Seeder selesai. Total {$totalUsers} akun berhasil dibuat.");
    }
}
