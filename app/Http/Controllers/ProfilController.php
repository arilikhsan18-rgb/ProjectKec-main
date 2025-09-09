<?php

namespace App\Http\Controllers;


use App\Models\Year;
use App\Models\Resident;
use App\Models\Lampid;
use App\Models\Fasum;
use App\Models\Infrastruktur;
use App\Models\Gender;
use App\Models\Geografis;
use App\Models\Education;
use App\Models\Occupation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function Index()
    {
        // --- 1. LOGIKA RINGKASAN UTAMA (dari tabel residents) ---
        $totalWarga = Resident::sum('jumlah');
        $jumlahPindahan = Resident::where('status_tinggal', 'pindahan')->sum('jumlah');
        $jumlahTetap = Resident::where('status_tinggal', 'tetap')->sum('jumlah');

       // --- 2. LOGIKA LAMPID ---
        // REVISI: Filter whereYear() dihapus untuk menampilkan total keseluruhan
        $queryLampid = Lampid::select('status', DB::raw('SUM(jumlah) as total_jumlah'))
                             ->groupBy('status')
                             ->get();
        $lampid = [
            'kelahiran' => 0, 'kematian' => 0, 'pindah' => 0, 'baru' => 0,
        ];
        foreach ($queryLampid as $data) {
            $key = strtolower($data->status);
            if (isset($lampid[$key])) {
                $lampid[$key] = $data->total_jumlah;
            }
        }

        // --- 3. LOGIKA KELOMPOK USIA (dari tabel years) & JENIS KELAMIN (dari tabel residents) ---
        // Jenis Kelamin lebih akurat dihitung dari tabel penduduk utama
        $jumlahLaki = Gender::where('gender', 'laki-laki')->sum('jumlah');
        $jumlahPerempuan = Gender::where('gender', 'perempuan')->sum('jumlah');
        
        // Kelompok Usia dari tabel rekapitulasi 'years'
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

        $kelompokUsia = ['Balita' => 0, 'Anak' => 0, 'Remaja' => 0, 'Dewasa' => 0, 'Lansia' => 0];
        foreach ($queryUsia as $data) {
            if ($data->kelompok_usia) {
                $kelompokUsia[$data->kelompok_usia] = $data->total_jumlah;
            }
        }

        // --- 4. LOGIKA PENDIDIKAN & PEKERJAAN (dari tabel masing-masing) ---
        $masihsekolah = Education::where('sekolah', 'masih sekolah')->sum('jumlah');
        $belumsekolah = Education::where('sekolah', 'belum sekolah')->sum('jumlah');
        $putussekolah = Education::where('sekolah', 'putus sekolah')->sum('jumlah');


        $bekerja = Occupation::where('pekerjaan', 'bekerja')->sum('jumlah');
        $tidakbekerja = Occupation::where('pekerjaan', 'tidak bekerja')->sum('jumlah');
        // --- 5. KIRIM SEMUA DATA KE VIEW (HANYA SATU  RETURN) ---
        return view('profil', compact(
            'totalWarga',
            'jumlahPindahan',
            'jumlahTetap',
            'lampid',
            'jumlahLaki',
            'jumlahPerempuan',
            'kelompokUsia',
            'masihsekolah',
            'belumsekolah',
            'putussekolah',
            'bekerja',
            'tidakbekerja'
        ));
    } 
}