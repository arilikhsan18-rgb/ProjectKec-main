<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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

        // Ambil ID dari setiap peran yang sudah ada di database
        // Pastikan Anda sudah menjalankan RoleSeeder terlebih dahulu
        $superAdminRole = Role::where('name', 'SUPERADMIN')->firstOrFail();
        $kecamatanRole  = Role::where('name', 'KECAMATAN')->firstOrFail();
        $kelurahanRole  = Role::where('name', 'KELURAHAN')->firstOrFail();
        $rwRole         = Role::where('name', 'RW')->firstOrFail();
        $rtRole         = Role::where('name', 'RT')->firstOrFail();

        $this->command->info('Memulai pembuatan semua akun pengguna...');

        // 1. Buat Akun Super Admin (untuk developer/pengelola sistem)
        User::create([
            'name'         => 'Super Administrator',
            'username'     => 'superadmin',
            'email'        => 'admin@simdawani.com',
            'password'     => Hash::make('superadmin123'), // Ganti dengan password yang sangat kuat!
            'status'       => 'active',
            'role_id'      => $superAdminRole->id,
            // parent_id null karena ini adalah level tertinggi
        ]);

        // 2. Buat Akun Kecamatan
        $kecamatanUser = User::create([
            'name'         => 'Admin Kecamatan Tawang',
            'username'     => 'kec_tawang',
            'email'        => 'tawang@gmail.com',
            'password'     => Hash::make('tawanghebat'),
            'status'       => 'active',
            'role_id'      => $kecamatanRole->id,
            'parent_id'    => null, // Bisa juga dihubungkan ke superadmin jika perlu
        ]);

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
                'name'           => 'Admin Kelurahan ' . $data['nama'],
                'username'       => 'kel_' . $namaKelurahanLower,
                'email'          => $namaKelurahanLower . '@kelurahan.com',
                'password'       => Hash::make('password'),
                'status'         => 'active',
                'role_id'        => $kelurahanRole->id,
                'parent_id'      => $kecamatanUser->id, // Menghubungkan ke Kecamatan
                'nama_kelurahan' => $data['nama'],
            ]);

            $rtDihasilkan = 0;
            for ($i = 1; $i <= $data['rw_count']; $i++) {
                $nomorRW = str_pad($i, 2, '0', STR_PAD_LEFT);

                // 4. Buat Akun RW
                $rwUser = User::create([
                    'name'           => 'Ketua RW ' . $nomorRW . ' - ' . $data['nama'],
                    'username'       => 'rw' . $nomorRW . '_' . $namaKelurahanLower,
                    'email'          => 'rw' . $nomorRW . '.' . $namaKelurahanLower . '@rw.com',
                    'password'       => Hash::make('password'),
                    'status'         => 'active',
                    'role_id'        => $rwRole->id,
                    'parent_id'      => $kelurahanUser->id, // Menghubungkan ke Kelurahan
                    'nama_kelurahan' => $data['nama'],
                    'nomor_rw'       => $nomorRW,
                ]);

                // Logika untuk membagi jumlah RT ke setiap RW se-proporsional mungkin
                $sisaRW = $data['rw_count'] - $i + 1;
                $sisaRT = $data['rt_count'] - $rtDihasilkan;
                $rtUntukRWIni = ($sisaRW > 0) ? round($sisaRT / $sisaRW) : 0;
                
                for ($j = 1; $j <= $rtUntukRWIni; $j++) {
                    // Pastikan tidak membuat RT lebih dari total yang seharusnya
                    if ($rtDihasilkan >= $data['rt_count']) break;
                    
                    $nomorRT = str_pad($j, 3, '0', STR_PAD_LEFT);
                    
                    // 5. Buat Akun RT
                    User::create([
                        'name'           => 'Ketua RT ' . $nomorRT . ' / RW ' . $nomorRW . ' - ' . $data['nama'],
                        'username'       => 'rt' . $nomorRT . '_rw' . $nomorRW . '_' . $namaKelurahanLower,
                        'email'          => 'rt' . $nomorRT . '.rw' . $nomorRW . '.' . $namaKelurahanLower . '@rt.com',
                        'password'       => Hash::make('password'),
                        'status'         => 'active',
                        'role_id'        => $rtRole->id,
                        'parent_id'      => $rwUser->id, // Menghubungkan ke RW
                        'nama_kelurahan' => $data['nama'],
                        'nomor_rw'       => $nomorRW,
                        'nomor_rt'       => $nomorRT,
                    ]);
                }
                $rtDihasilkan += $rtUntukRWIni;
            }
        }
        
        $totalUsers = User::count();
        $this->command->info("Seeder selesai. Total {$totalUsers} akun berhasil dibuat.");
    }
}
