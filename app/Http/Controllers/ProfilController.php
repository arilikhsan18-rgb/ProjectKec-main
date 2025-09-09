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
use Illuminate\Support\Facades\Auth; // <-- Import Auth


class ProfilController extends Controller
{
    public function Index(Request $request)
    {
        $loggedInUser = Auth::user();
        $filters = $request->only(['kelurahan_id', 'rw_id', 'rt_id']);

        // --- 1. PERSIAPAN FILTER & JUDUL BERDASARKAN HAK AKSES ---
        $headerTitle = "Profil Kependudukan Kecamatan Tawang";
        $headerSubtitle = "Data statistik agregat seluruh wilayah";
        
        // Inisialisasi daftar dropdown
        $kelurahans = collect();
        $rws = collect();
        $rts = collect();

        // Tentukan cakupan ID RT yang boleh diakses
        $accessibleRtIds = [];

        if ($loggedInUser->hasRole('SUPERADMIN') || $loggedInUser->hasRole('KECAMATAN')) {
            // SUPERADMIN & KECAMATAN bisa melihat semua dan menggunakan filter manual
            $kelurahans = User::whereHas('roles', fn($q) => $q->where('name', 'KELURAHAN'))->orderBy('name')->get();
            $rws = User::whereHas('roles', fn($q) => $q->where('name', 'RW'))->orderBy('name')->get();
            $rts = User::whereHas('roles', fn($q) => $q->where('name', 'RT'))->orderBy('name')->get();
            
            // Logika filter manual dari request
            if (!empty($filters['rt_id'])) {
                $rtUser = User::find($filters['rt_id']);
                // REVISI: Gunakan optional() untuk menghindari error jika parent tidak ada
                $headerTitle = "Profil " . ($rtUser->name ?? 'RT');
                $headerSubtitle = (optional(optional($rtUser)->parent)->parent->name ?? '') . ", " . (optional($rtUser)->parent->name ?? '');
                $accessibleRtIds = [$filters['rt_id']];
            } elseif (!empty($filters['rw_id'])) {
                $rwUser = User::find($filters['rw_id']);
                $headerTitle = "Profil " . ($rwUser->name ?? 'RW');
                $headerSubtitle = optional($rwUser)->parent->name ?? '';
                $accessibleRtIds = User::where('parent_id', $filters['rw_id'])->pluck('id')->toArray();
            } elseif (!empty($filters['kelurahan_id'])) {
                $kelurahanUser = User::find($filters['kelurahan_id']);
                $headerTitle = "Profil " . ($kelurahanUser->name ?? 'Kelurahan');
                $accessibleRtIds = User::whereIn('parent_id', function($query) use ($filters) {
                    $query->select('id')->from('users')->where('parent_id', $filters['kelurahan_id']);
                })->pluck('id')->toArray();
            }

        } elseif ($loggedInUser->hasRole('KELURAHAN')) {
            $headerTitle = "Profil " . $loggedInUser->name;
            $kelurahans = collect([$loggedInUser]);
            $rws = User::where('parent_id', $loggedInUser->id)->orderBy('name')->get();
            $accessibleRtIds = User::whereIn('parent_id', $rws->pluck('id'))->pluck('id')->toArray();
            $rts = User::whereIn('id', $accessibleRtIds)->orderBy('name')->get();
            $filters['kelurahan_id'] = $loggedInUser->id; // Kunci filter

        } elseif ($loggedInUser->hasRole('RW')) {
            $headerTitle = "Profil " . $loggedInUser->name;
            // REVISI: Gunakan optional() untuk menghindari error
            $headerSubtitle = optional($loggedInUser->parent)->name;
            $rws = collect([$loggedInUser]);
            $kelurahans = collect([$loggedInUser->parent]);
            $accessibleRtIds = User::where('parent_id', $loggedInUser->id)->pluck('id')->toArray();
            $rts = User::whereIn('id', $accessibleRtIds)->orderBy('name')->get();
            $filters['rw_id'] = $loggedInUser->id; // Kunci filter

        } elseif ($loggedInUser->hasRole('RT')) {
            $headerTitle = "Profil " . $loggedInUser->name;
            // REVISI: Gunakan optional() untuk keamanan ganda
            $headerSubtitle = (optional(optional($loggedInUser)->parent)->parent->name ?? '') . ", " . (optional($loggedInUser)->parent->name ?? '');
            $rts = collect([$loggedInUser]);
            $rws = collect([$loggedInUser->parent]);
            // REVISI: Pastikan parent dan grandparent ada sebelum membuat collection
            $kelurahans = collect(optional(optional($loggedInUser)->parent)->parent ? [$loggedInUser->parent->parent] : []);
            $accessibleRtIds = [$loggedInUser->id];
            $filters['rt_id'] = $loggedInUser->id; // Kunci filter
        }

        // --- 2. MEMBUAT QUERY BUILDER DASAR ---
        $residentQuery = Resident::query();
        $lampidQuery = Lampid::query();
        $yearsQuery = DB::table('years');
        $genderQuery = Gender::query();
        $educationQuery = Education::query();
        $occupationQuery = Occupation::query();

        // --- 3. MENERAPKAN FILTER WAJIB (JIKA ADA) ---
        if (!empty($accessibleRtIds)) {
            $residentQuery->whereIn('user_id', $accessibleRtIds);
            $lampidQuery->whereIn('user_id', $accessibleRtIds);
            $yearsQuery->whereIn('user_id', $accessibleRtIds);
            $genderQuery->whereIn('user_id', $accessibleRtIds);
            $educationQuery->whereIn('user_id', $accessibleRtIds);
            $occupationQuery->whereIn('user_id', $accessibleRtIds);
        }

        // --- 4. EKSEKUSI SEMUA QUERY SETELAH DIFILTER ---
        // (Semua logika query SUM, COUNT, CASE WHEN Anda tetap sama persis seperti sebelumnya)
        $totalWarga = (clone $residentQuery)->sum('jumlah');
        $jumlahPindahan = (clone $residentQuery)->where('status_tinggal', 'pindahan')->sum('jumlah');
        $jumlahTetap = (clone $residentQuery)->where('status_tinggal', 'tetap')->sum('jumlah');
        // ... dst untuk semua query lainnya ...
        $lampidData = (clone $lampidQuery)->select('status', DB::raw('SUM(jumlah) as total_jumlah'))->groupBy('status')->get();
        $lampid = ['kelurahan' => 0, 'kematian' => 0, 'pindah' => 0, 'baru' => 0];
        foreach ($lampidData as $data) { if(isset($lampid[strtolower($data->status)])) $lampid[strtolower($data->status)] = $data->total_jumlah; }
        $jumlahLaki = (clone $genderQuery)->where('gender', 'laki-laki')->sum('jumlah');
        $jumlahPerempuan = (clone $genderQuery)->where('gender', 'perempuan')->sum('jumlah');
        $usiaData = (clone $yearsQuery)->select(DB::raw("CASE WHEN (YEAR(CURDATE()) - tahun_lahir) <= 5 THEN 'Balita' WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 6 AND 12 THEN 'Anak' WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 13 AND 18 THEN 'Remaja' WHEN (YEAR(CURDATE()) - tahun_lahir) BETWEEN 19 AND 55 THEN 'Dewasa' ELSE 'Lansia' END as kelompok_usia, SUM(jumlah) as total_jumlah"))->groupBy('kelompok_usia')->get();
        $kelompokUsia = ['Balita' => 0, 'Anak' => 0, 'Remaja' => 0, 'Dewasa' => 0, 'Lansia' => 0];
        foreach ($usiaData as $data) { if ($data->kelompok_usia && isset($kelompokUsia[$data->kelompok_usia])) { $kelompokUsia[$data->kelompok_usia] = $data->total_jumlah; } }
        $masihsekolah = (clone $educationQuery)->where('sekolah', 'masih sekolah')->sum('jumlah');
        $belumsekolah = (clone $educationQuery)->where('sekolah', 'belum sekolah')->sum('jumlah');
        $putussekolah = (clone $educationQuery)->where('sekolah', 'putus sekolah')->sum('jumlah');
        $bekerja = (clone $occupationQuery)->where('pekerjaan', 'bekerja')->sum('jumlah');
        $tidakbekerja = (clone $occupationQuery)->where('pekerjaan', 'tidak bekerja')->sum('jumlah');


        // --- 5. KIRIM DATA KE VIEW ---
        return view('profil', compact(
            'headerTitle', 'headerSubtitle', 'filters',
            'kelurahans', 'rws', 'rts', 'loggedInUser',
            'totalWarga', 'jumlahPindahan', 'jumlahTetap', 'lampid',
            'jumlahLaki', 'jumlahPerempuan', 'kelompokUsia',
            'masihsekolah', 'belumsekolah', 'putussekolah', 'bekerja', 'tidakbekerja'
        ));
    } 
}