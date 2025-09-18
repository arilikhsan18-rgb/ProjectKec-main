<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\Fasilitas; // <-- TAMBAHKAN: Menggunakan model Fasilitas
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $loggedInUser = Auth::user();
        $filters = $request->only(['kelurahan_id', 'rw_id', 'rt_id']);

        // --- 1. PERSIAPAN FILTER & JUDUL BERDASARKAN HAK AKSES ---
        $headerTitle = "Profil Kependudukan Kecamatan Tawang";
        $headerSubtitle = "Data statistik agregat seluruh wilayah";
        
        $kelurahans = collect();
        $rws = collect();
        $rts = collect();
        $accessibleRtIds = [];

        if ($loggedInUser->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) {
            $kelurahans = User::whereHas('roles', fn($q) => $q->where('name', 'KELURAHAN'))->orderBy('name')->get();
            $rws = User::whereHas('roles', fn($q) => $q->where('name', 'RW'))->orderBy('name')->get();
            $rts = User::whereHas('roles', fn($q) => $q->where('name', 'RT'))->orderBy('name')->get();
            
            if (!empty($filters['rt_id'])) {
                $rtUser = User::find($filters['rt_id']);
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
            $filters['kelurahan_id'] = $loggedInUser->id;
        } elseif ($loggedInUser->hasRole('RW')) {
            $headerTitle = "Profil " . $loggedInUser->name;
            $headerSubtitle = optional($loggedInUser->parent)->name;
            $rws = collect([$loggedInUser]);
            $kelurahans = collect([$loggedInUser->parent]);
            $accessibleRtIds = User::where('parent_id', $loggedInUser->id)->pluck('id')->toArray();
            $rts = User::whereIn('id', $accessibleRtIds)->orderBy('name')->get();
            $filters['rw_id'] = $loggedInUser->id;
        } elseif ($loggedInUser->hasRole('RT')) {
            $headerTitle = "Profil " . $loggedInUser->name;
            $headerSubtitle = (optional(optional($loggedInUser)->parent)->parent->name ?? '') . ", " . (optional($loggedInUser)->parent->name ?? '');
            $rts = collect([$loggedInUser]);
            $rws = collect([$loggedInUser->parent]);
            $kelurahans = collect(optional(optional($loggedInUser)->parent)->parent ? [$loggedInUser->parent->parent] : []);
            $accessibleRtIds = [$loggedInUser->id];
            $filters['rt_id'] = $loggedInUser->id;
        }

        // --- 2. MEMBUAT QUERY BUILDER DASAR ---
        $pendudukQuery = Penduduk::query();
        $fasilitasQuery = Fasilitas::query(); // <-- TAMBAHAN: Query builder untuk fasilitas

        // --- 3. MENERAPKAN FILTER WAJIB (JIKA ADA) ---
        if (!empty($accessibleRtIds)) {
            $pendudukQuery->whereIn('user_id', $accessibleRtIds);
            $fasilitasQuery->whereIn('user_id', $accessibleRtIds); // <-- TAMBAHAN: Terapkan filter ke fasilitas
        }

        // --- 4. EKSEKUSI SEMUA QUERY SETELAH DIFILTER ---
        // ... (semua perhitungan data penduduk tetap sama) ...
        $totalWarga = (clone $pendudukQuery)->count();
        $jumlahPindahan = (clone $pendudukQuery)->where('resident', 'pindahan')->count();
        $jumlahTetap = (clone $pendudukQuery)->where('resident', 'tetap')->count();
        $lampidData = (clone $pendudukQuery)->select('lampid', DB::raw('count(*) as total'))->whereNotNull('lampid')->groupBy('lampid')->pluck('total', 'lampid');
        $lampid = ['kelahiran' => $lampidData['kelahiran'] ?? 0, 'kematian'  => $lampidData['kematian'] ?? 0, 'pindah' => $lampidData['pindah'] ?? 0, 'datang' => $lampidData['datang'] ?? 0];
        $jumlahLaki = (clone $pendudukQuery)->where('gender', 'laki-laki')->count();
        $jumlahPerempuan = (clone $pendudukQuery)->where('gender', 'perempuan')->count();
        $currentYear = date('Y');
        $kelompokUsia = ['Balita' => (clone $pendudukQuery)->whereRaw("$currentYear - year BETWEEN 0 AND 5")->count(), 'Anak' => (clone $pendudukQuery)->whereRaw("$currentYear - year BETWEEN 6 AND 12")->count(), 'Remaja' => (clone $pendudukQuery)->whereRaw("$currentYear - year BETWEEN 13 AND 18")->count(), 'Dewasa' => (clone $pendudukQuery)->whereRaw("$currentYear - year BETWEEN 19 AND 55")->count(), 'Lansia' => (clone $pendudukQuery)->whereRaw("$currentYear - year >= 56")->count()];
        $masihsekolah = (clone $pendudukQuery)->where('education', 'masih sekolah')->count();
        $belumsekolah = (clone $pendudukQuery)->where('education', 'belum sekolah')->count();
        $putussekolah = (clone $pendudukQuery)->where('education', 'putus sekolah')->count();
        $bekerja = (clone $pendudukQuery)->where('occupation', 'bekerja')->count();
        $tidakbekerja = (clone $pendudukQuery)->where('occupation', 'tidak bekerja')->count();
        $usaha = (clone $pendudukQuery)->where('occupation', 'usaha')->count();

        // <-- TAMBAHAN: Menghitung data fasilitas -->
        $fasilitasTotals = (clone $fasilitasQuery)
            ->select('jenis_fasilitas', DB::raw('count(*) as total'))
            ->groupBy('jenis_fasilitas')
            ->pluck('total', 'jenis_fasilitas');

        // --- 5. KIRIM DATA KE VIEW ---
        return view('profil', compact(
            'headerTitle', 'headerSubtitle', 'filters',
            'kelurahans', 'rws', 'rts', 'loggedInUser',
            'totalWarga', 'jumlahPindahan', 'jumlahTetap', 'lampid',
            'jumlahLaki', 'jumlahPerempuan', 'kelompokUsia',
            'masihsekolah', 'belumsekolah', 'putussekolah', 'bekerja', 'tidakbekerja', 'usaha',
            'fasilitasTotals' // <-- TAMBAHAN: Kirim data fasilitas ke view
        ));
    }
}

