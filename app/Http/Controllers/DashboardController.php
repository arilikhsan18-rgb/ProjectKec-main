<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use App\Models\Year;
use App\Models\Education;
use App\Models\Occupation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ==================================================================
        // LANGKAH 1: TENTUKAN HAK AKSES (SIAPA MELIHAT DATA SIAPA)
        // ==================================================================
        $rtIds = $this->getAuthorizedRtIds($user);

        // "Closure" untuk menerapkan filter hak akses ke semua query kita nanti
        $applyAuthFilter = function ($query) use ($rtIds) {
            if ($rtIds !== null) {
                $query->whereIn('user_id', $rtIds);
            }
        };

        // ==================================================================
        // LANGKAH 2: HITUNG DATA UNTUK KARTU STATISTIK (KPI)
        // ==================================================================
        $stats = [];
        // Perhitungan Total Warga yang BENAR dan AMAN
        $stats['totalWarga'] = Resident::query()->tap($applyAuthFilter)->sum('jumlah');
        $stats['totalPengguna'] = $this->countUsersInHierarchy($user);
        $stats['namaUser'] = $user->name;
        $stats['roleUser'] = $user->getRoleNames()->first();

        // ==================================================================
        // LANGKAH 3: AMBIL DATA UNTUK GRAFIK (DENGAN FILTER KEAMANAN)
        // ==================================================================
        $charts = [];
        
        // DATA GRAFIK RENTANG USIA (SUDAH AMAN)
        $dataGrafikUsia = Year::query()->tap($applyAuthFilter)
            ->selectRaw("
                CASE
                    WHEN tahun_lahir BETWEEN 2020 AND " . date('Y') . " THEN 'Usia 0-5 Thn'
                    WHEN tahun_lahir BETWEEN 2008 AND 2019 THEN 'Usia 6-17 Thn'
                    WHEN tahun_lahir BETWEEN 1966 AND 2007 THEN 'Usia 18-59 Thn'
                    ELSE 'Usia 60+ Thn'
                END as rentang_usia,
                SUM(jumlah) as jumlah_warga
            ")
            ->groupBy('rentang_usia')->orderBy('rentang_usia')->get();
        $charts['labelsUsia'] = $dataGrafikUsia->pluck('rentang_usia');
        $charts['dataUsia'] = $dataGrafikUsia->pluck('jumlah_warga');

        // DATA GRAFIK PENDIDIKAN (SUDAH AMAN)
        $dataGrafikPendidikan = Education::query()->tap($applyAuthFilter)
            ->select('sekolah', DB::raw('SUM(jumlah) as total'))
            ->groupBy('sekolah')->get();
        $charts['labelsPendidikan'] = $dataGrafikPendidikan->pluck('sekolah')->map(fn($s) => ucwords($s));
        $charts['dataPendidikan'] = $dataGrafikPendidikan->pluck('total');
        
        // DATA GRAFIK PEKERJAAN (SUDAH AMAN)
        $dataGrafikPekerjaan = Occupation::query()->tap($applyAuthFilter)
            ->select('pekerjaan', DB::raw('SUM(jumlah) as total'))
            ->groupBy('pekerjaan')->get();
        $charts['labelsPekerjaan'] = $dataGrafikPekerjaan->pluck('pekerjaan')->map(fn($p) => ucwords($p));
        $charts['dataPekerjaan'] = $dataGrafikPekerjaan->pluck('total');

        // DATA GRAFIK KEPENDUDUKAN (SUDAH AMAN)
        $dataGrafikKependudukan = Resident::query()->tap($applyAuthFilter)
            ->select('status_tinggal', DB::raw('SUM(jumlah) as total'))
            ->groupBy('status_tinggal')->get();
        $charts['labelsKependudukan'] = $dataGrafikKependudukan->pluck('status_tinggal')->map(fn($s) => ucwords($s));
        $charts['dataKependudukan'] = $dataGrafikKependudukan->pluck('total');


        // ==================================================================
        // LANGKAH 4: GABUNGKAN SEMUA DATA DAN KIRIM KE VIEW
        // ==================================================================
        return view('dashboard', array_merge($stats, $charts));
    }

    // --- Helper Methods (Fungsi Pembantu) ---

    private function getAuthorizedRtIds(User $user): ?array
    {
        if ($user->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) return null;
        if ($user->hasRole('RW')) return User::where('parent_id', $user->id)->pluck('id')->toArray();
        if ($user->hasRole('KELURAHAN')) {
            $rwIds = User::where('parent_id', $user->id)->pluck('id');
            return User::whereIn('parent_id', $rwIds)->pluck('id')->toArray();
        }
        if ($user->hasRole('RT')) return [$user->id];
        return [];
    }

    private function countUsersInHierarchy(User $user): int
    {
        if ($user->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) {
            return User::whereHas('roles', fn($q) => $q->where('name', '!=', 'SUPERADMIN'))->count();
        }
        if ($user->hasRole('KELURAHAN')) {
            $rwIds = User::where('parent_id', $user->id)->pluck('id');
            $rtCount = User::whereIn('parent_id', $rwIds)->count();
            return $rwIds->count() + $rtCount;
        }
        if ($user->hasRole('RW')) {
            return User::where('parent_id', $user->id)->count();
        }
        return 0;
    }
}

