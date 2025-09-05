<?php

namespace App\Http\Controllers;

// Model yang digunakan adalah Year, bukan Gender atau Year
use App\Models\Year;
use App\Models\Resident;
use App\Models\Lampid;
use App\Models\Fasum;
use App\Models\Infrastruktur;
use App\Models\Gender;
use App\Models\Geografis;
use App\Models\Education; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function Index()
    {
        // --- LOGIKA BARU UNTUK RINGKASAN UTAMA ---

        // 1. Hitung total semua warga/penduduk
        $totalWarga = Resident::count();

        // 2. Hitung jumlah warga pindahan dan tetap
        $jumlahPindahan = Resident::where('status_tinggal', 'pindahan')->count();
        $jumlahTetap = Resident::where('status_tinggal', 'tetap')->count();

        // --- LOGIKA BARU UNTUK LAMPID ---

        // 2. Query untuk menghitung jumlah setiap status LAMPID di tahun ini // Mengambil tahun saat ini, misal: 2025
        $queryLampid = Lampid::select('status', DB::raw('count(*) as jumlah'))
                             ->groupBy('status')
                             ->get();

        // 3. Siapkan array untuk menampung hasil hitungan
        $lampid = [
            'Lahir'  => 0,
            'Mati'   => 0,
            'pindah' => 0,
            'baru' => 0,
        ];

        foreach ($queryLampid as $data) {
            if (isset($lampid[$data->status])) {
                $lampid[$data->status] = $data->jumlah;
            }
        }
        // --- LOGIKA GEOGRAFIS---
        $luas = Geografis::where('luas')->count();

        // --- LOGIKA TAHUN KELAHIRAN DAN GENDER ---

        // PERBAIKAN 1: Hitung jenis kelamin dari tabel penduduk utama ('Years')
        // Ini jauh lebih akurat dan standar.
        $jumlahLaki = Gender::where('gender', 'laki-laki')->count();
        $jumlahPerempuan = Gender::where('gender', 'perempuan')->count();

        // Query untuk kelompok usia dari tabel rekapitulasi 'years' sudah benar
        $queryUsia = DB::table('years')
            ->select(DB::raw("
                CASE
                    WHEN (YEAR(CURDATE()) - tahun_lahir) <= 5 THEN 'Balita'
                    WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 6 AND 12 THEN 'Anak'
                    WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 13 AND 18 THEN 'Remaja'
                    WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 19 AND 55 THEN 'Dewasa'
                    ELSE 'Lansia'
                END as kelompok_usia,
                SUM(jumlah) as total_jumlah 
            "))
            ->groupBy('kelompok_usia')
            ->get();

        // Olah hasil query menjadi array
        $kelompokUsia = [
            'Balita' => 0, 'Anak' => 0, 'Remaja' => 0, 'Dewasa' => 0, 'Lansia' => 0,
        ];

        foreach ($queryUsia as $data) {
            if ($data->kelompok_usia) {
                // PERBAIKAN 2: Gunakan alias yang benar -> total_jumlah
                $kelompokUsia[$data->kelompok_usia] = $data->total_jumlah;
            }
        }

        // --- LOGIKA SELESAI ---

        // Kirim semua data yang sudah dihitung ke view
        return view('profil', compact(
            'jumlahLaki',
            'jumlahPerempuan',
            'kelompokUsia'
        ));

        // --- LOGIKA BARU UNTUK PENDIDIKAN & PEKERJAAN DARI TABEL MASING-MASING ---

        // 3. Ambil semua data rekapitulasi dari tabel 'educations'
        $queryPendidikan = Education::all();
        
        // Siapkan array untuk menampung hasil hitungan pendidikan
        $pendidikan = [
            'Belum Sekolah' => 0,
            'Masih Sekolah' => 0,
            'Putus Sekolah' => 0,
        ];
        foreach ($queryPendidikan as $data) {
            // Gunakan kolom 'pendidikan' dari tabel 'educations'
            if (isset($pendidikan[$data->pendidikan])) {
                $pendidikan[$data->pendidikan] = $data->jumlah;
            }
        }

        // 4. Ambil semua data rekapitulasi dari tabel 'occupations'
        $queryPekerjaan = Occupation::all();

        // Siapkan array untuk menampung hasil hitungan pekerjaan
        $pekerjaan = [
            'Bekerja' => 0,
            'Tidak Bekerja' => 0,
        ];
        foreach ($queryPekerjaan as $data) {
             // Gunakan kolom 'pekerjaan' dari tabel 'occupations'
             if (isset($pekerjaan[$data->pekerjaan])) {
                $pekerjaan[$data->pekerjaan] = $data->jumlah;
            }
        }

        // --- AKHIR LOGIKA BARU ---

        // Kirim semua data ke view
        return view('profil', compact(
            'totalWarga',       // <-- Tambahkan ini
            'jumlahPindahan',    // <-- Tambahkan ini
            'jumlahTetap',      // <-- Tambahkan ini
            'jumlahLaki',
            'jumlahPerempuan',
            'kelompokUsia',
            'pendidikan',
            'pekerjaan'
        ));
    } 
}