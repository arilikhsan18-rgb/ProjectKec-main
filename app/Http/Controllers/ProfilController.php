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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function Index(Request $request)
    {
        // --- 1. PERSIAPAN FILTER & JUDUL DINAMIS ---
        $filters = $request->only(['kelurahan_id', 'rw_id', 'rt_id']);
        $headerTitle = "Profil Kependudukan Kecamatan Tawang"; // Judul Default
        $headerSubtitle = "Data statistik agregat seluruh wilayah";

        // REVISI: Ambil data untuk dropdown dari model User berdasarkan role
        $kelurahans = User::whereHas('roles', fn($q) => $q->where('name', 'KELURAHAN'))->orderBy('name')->get();
        $rws = User::whereHas('roles', fn($q) => $q->where('name', 'RW'))->orderBy('name')->get();
        $rts = User::whereHas('roles', fn($q) => $q->where('name', 'RT'))->orderBy('name')->get();

        // --- 2. MEMBUAT QUERY BUILDER DASAR ---
        $residentQuery = Resident::query();
        $lampidQuery = Lampid::query();
        $yearsQuery = DB::table('years');
        $genderQuery = Gender::query();
        $educationQuery = Education::query();
        $occupationQuery = Occupation::query();

        // --- 3. MENERAPKAN FILTER JIKA ADA ---
        if (!empty($filters['rt_id'])) {
            $rtUser = User::find($filters['rt_id']);
            $headerTitle = "Profil " . ($rtUser->name ?? 'RT Tidak Ditemukan');
            $headerSubtitle = ($rtUser->parent->parent->name ?? '') . ", " . ($rtUser->parent->name ?? '');
            
            $rtIdsToFilter = [$filters['rt_id']];

        } elseif (!empty($filters['rw_id'])) {
            $rwUser = User::find($filters['rw_id']);
            $headerTitle = "Profil " . ($rwUser->name ?? 'RW Tidak Ditemukan');
            $headerSubtitle = ($rwUser->parent->name ?? '');

            $rtIdsToFilter = User::where('parent_id', $filters['rw_id'])->pluck('id');

        } elseif (!empty($filters['kelurahan_id'])) {
            $kelurahanUser = User::find($filters['kelurahan_id']);
            $headerTitle = "Profil " . ($kelurahanUser->name ?? 'Kelurahan Tidak Ditemukan');
            $headerSubtitle = "";

            $rtIdsToFilter = User::whereIn('parent_id', function($query) use ($filters) {
                $query->select('id')->from('users')->where('parent_id', $filters['kelurahan_id']);
            })->pluck('id');
        }

        if (isset($rtIdsToFilter)) {
            $residentQuery->whereIn('user_id', $rtIdsToFilter);
            $lampidQuery->whereIn('user_id', $rtIdsToFilter);
            $yearsQuery->whereIn('user_id', $rtIdsToFilter);
            $genderQuery->whereIn('user_id', $rtIdsToFilter);
            $educationQuery->whereIn('user_id', $rtIdsToFilter);
            $occupationQuery->whereIn('user_id', $rtIdsToFilter);
        }

        // --- 4. EKSEKUSI SEMUA QUERY SETELAH DIFILTER ---
        
        // Ringkasan Utama
        $totalWarga = (clone $residentQuery)->sum('jumlah');
        $jumlahPindahan = (clone $residentQuery)->where('status_tinggal', 'pindahan')->sum('jumlah');
        $jumlahTetap = (clone $residentQuery)->where('status_tinggal', 'tetap')->sum('jumlah');

        // LAMPID
        $lampidData = (clone $lampidQuery)->select('status', DB::raw('SUM(jumlah) as total_jumlah'))->groupBy('status')->get();
        $lampid = ['kelahiran' => 0, 'kematian' => 0, 'pindah' => 0, 'baru' => 0];
        foreach ($lampidData as $data) { if(isset($lampid[strtolower($data->status)])) $lampid[strtolower($data->status)] = $data->total_jumlah; }

        // Gender
        $jumlahLaki = (clone $genderQuery)->where('gender', 'laki-laki')->sum('jumlah');
        $jumlahPerempuan = (clone $genderQuery)->where('gender', 'perempuan')->sum('jumlah');

        // REVISI (INI PERBAIKANNYA): Mengisi query CASE WHEN yang lengkap
        $usiaData = (clone $yearsQuery)->select(DB::raw("
            CASE
                WHEN (YEAR(CURDATE()) - tahun_lahir) <= 5 THEN 'Balita'
                WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 6 AND 12 THEN 'Anak'
                WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 13 AND 18 THEN 'Remaja'
                WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 19 AND 55 THEN 'Dewasa'
                ELSE 'Lansia'
            END as kelompok_usia,
            SUM(jumlah) as total_jumlah
        "))->groupBy('kelompok_usia')->get();

        $kelompokUsia = ['Balita' => 0, 'Anak' => 0, 'Remaja' => 0, 'Dewasa' => 0, 'Lansia' => 0];
        foreach ($usiaData as $data) {
            if ($data->kelompok_usia && isset($kelompokUsia[$data->kelompok_usia])) {
                $kelompokUsia[$data->kelompok_usia] = $data->total_jumlah;
            }
        }

        // Pendidikan & Pekerjaan
        $masihsekolah = (clone $educationQuery)->where('sekolah', 'masih sekolah')->sum('jumlah');
        $belumsekolah = (clone $educationQuery)->where('sekolah', 'belum sekolah')->sum('jumlah');
        $putussekolah = (clone $educationQuery)->where('sekolah', 'putus sekolah')->sum('jumlah');
        $bekerja = (clone $occupationQuery)->where('pekerjaan', 'bekerja')->sum('jumlah');
        $tidakbekerja = (clone $occupationQuery)->where('pekerjaan', 'tidak bekerja')->sum('jumlah');

        // --- 5. KIRIM DATA KE VIEW ---
        return view('profil', compact(
            'headerTitle', 'headerSubtitle', 'filters',
            'kelurahans', 'rws', 'rts',
            'totalWarga', 'jumlahPindahan', 'jumlahTetap', 'lampid',
            'jumlahLaki', 'jumlahPerempuan', 'kelompokUsia',
            'masihsekolah', 'belumsekolah', 'putussekolah', 'bekerja', 'tidakbekerja'
        ));
    } 
}