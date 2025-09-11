<?php

namespace App\Http\Controllers;

use App\Models\Penduduk; // <-- DIUBAH: Menggunakan model Penduduk
use App\Models\User;
use Illuminate\Http\Request; // <-- TAMBAHAN: Dibutuhkan untuk menerima input filter
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // DIUBAH: Method index sekarang menerima Request untuk handle filter
    public function index(Request $request)
    {
        $user = Auth::user();
        $filters = $request->only(['kelurahan_id', 'rw_id', 'rt_id']);

        // ==================================================================
        // LANGKAH 1: TENTUKAN HAK AKSES DAN TERAPKAN FILTER MANUAL
        // ==================================================================
        
        // Inisialisasi variabel untuk dropdown filter
        $kelurahans = collect();
        $rws = collect();
        $rts = collect();
        $rtIds = [];

        if ($user->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) {
            // Jika Superadmin atau Kecamatan, mereka bisa melihat semua dan menggunakan filter.
            // Siapkan data untuk dropdown filter.
            $kelurahans = User::whereHas('roles', fn($q) => $q->where('name', 'KELURAHAN'))->orderBy('name')->get();
            $rws = User::whereHas('roles', fn($q) => $q->where('name', 'RW'))->orderBy('name')->get();
            $rts = User::whereHas('roles', fn($q) => $q->where('name', 'RT'))->orderBy('name')->get();

            // Tentukan ID RT yang akan difilter berdasarkan input dari form
            if (!empty($filters['rt_id'])) {
                $rtIds = [$filters['rt_id']];
            } elseif (!empty($filters['rw_id'])) {
                $rtIds = User::where('parent_id', $filters['rw_id'])->pluck('id')->toArray();
            } elseif (!empty($filters['kelurahan_id'])) {
                $rwIdsInKelurahan = User::where('parent_id', $filters['kelurahan_id'])->pluck('id');
                $rtIds = User::whereIn('parent_id', $rwIdsInKelurahan)->pluck('id')->toArray();
            } else {
                // Jika tidak ada filter, mereka melihat semua data (null berarti tanpa filter)
                $rtIds = null;
            }
        } else {
            // Jika bukan admin, gunakan hak akses standar dari helper method
            $rtIds = $this->getAuthorizedRtIds($user);
        }

        // "Closure" untuk menerapkan filter hak akses ke semua query kita nanti
        $applyAuthFilter = function ($query) use ($rtIds) {
            if ($rtIds !== null) {
                $query->whereIn('user_id', $rtIds);
            }
        };

        // ==================================================================
        // LANGKAH 2: HITUNG DATA UNTUK KARTU STATISTIK (KPI)
        // (Logika ini tidak berubah, hanya sumber datanya yang sudah terfilter)
        // ==================================================================
        $stats = [];
        $stats['totalWarga'] = Penduduk::query()->tap($applyAuthFilter)->count();
        $stats['totalPengguna'] = $this->countUsersInHierarchy($user); // Total pengguna tetap sesuai hierarki
        $stats['namaUser'] = $user->name;
        $stats['roleUser'] = $user->getRoleNames()->first();

        // ==================================================================
        // LANGKAH 3: AMBIL DATA UNTUK GRAFIK (DENGAN SUMBER DARI 'penduduks')
        // (Logika ini tidak berubah, hanya sumber datanya yang sudah terfilter)
        // ==================================================================
        $charts = [];
        $currentYear = date('Y');
        
        $dataGrafikUsia = Penduduk::query()->tap($applyAuthFilter)
            ->selectRaw("
                CASE
                    WHEN ($currentYear - year) BETWEEN 0 AND 5 THEN 'Usia 0-5 Thn'
                    WHEN ($currentYear - year) BETWEEN 6 AND 17 THEN 'Usia 6-17 Thn'
                    WHEN ($currentYear - year) BETWEEN 18 AND 59 THEN 'Usia 18-59 Thn'
                    ELSE 'Usia 60+ Thn'
                END as rentang_usia,
                count(*) as jumlah_warga
            ")
            ->groupBy('rentang_usia')->orderBy('rentang_usia')->get();
        $charts['labelsUsia'] = $dataGrafikUsia->pluck('rentang_usia');
        $charts['dataUsia'] = $dataGrafikUsia->pluck('jumlah_warga');

        $dataGrafikPendidikan = Penduduk::query()->tap($applyAuthFilter)
            ->select('education', DB::raw('count(*) as total'))
            ->groupBy('education')->get();
        $charts['labelsPendidikan'] = $dataGrafikPendidikan->pluck('education')->map(fn($s) => ucwords($s));
        $charts['dataPendidikan'] = $dataGrafikPendidikan->pluck('total');
        
        $dataGrafikPekerjaan = Penduduk::query()->tap($applyAuthFilter)
            ->select('occupation', DB::raw('count(*) as total'))
            ->groupBy('occupation')->get();
        $charts['labelsPekerjaan'] = $dataGrafikPekerjaan->pluck('occupation')->map(fn($p) => ucwords($p));
        $charts['dataPekerjaan'] = $dataGrafikPekerjaan->pluck('total');

        $dataGrafikKependudukan = Penduduk::query()->tap($applyAuthFilter)
            ->select('resident', DB::raw('count(*) as total'))
            ->groupBy('resident')->get();
        $charts['labelsKependudukan'] = $dataGrafikKependudukan->pluck('resident')->map(fn($s) => ucwords($s));
        $charts['dataKependudukan'] = $dataGrafikKependudukan->pluck('total');

        // ==================================================================
        // LANGKAH 4: GABUNGKAN SEMUA DATA DAN KIRIM KE VIEW
        // ==================================================================
        // TAMBAHAN: Kirim data filter dan dropdown ke view
        return view('dashboard', array_merge($stats, $charts, [
            'kelurahans' => $kelurahans,
            'rws' => $rws,
            'rts' => $rts,
            'filters' => $filters
        ]));
    }

    // --- Helper Methods (Fungsi Pembantu) ---
    // (Logika ini tidak berubah, sudah benar)

    private function getAuthorizedRtIds(User $user): ?array
    {
        if ($user->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) return null; // null berarti tidak ada filter
        if ($user->hasRole('KELURAHAN')) {
            $rwIds = User::where('parent_id', $user->id)->pluck('id');
            return User::whereIn('parent_id', $rwIds)->pluck('id')->toArray();
        }
        if ($user->hasRole('RW')) {
            return User::where('parent_id', $user->id)->pluck('id')->toArray();
        }
        if ($user->hasRole('RT')) {
            return [$user->id];
        }
        return []; // Default, tidak bisa melihat apa-apa
    }

    private function countUsersInHierarchy(User $user): int
    {
        if ($user->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) {
            // Hitung semua user kecuali superadmin lain
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
        return 0; // RT tidak punya bawahan
    }
}

