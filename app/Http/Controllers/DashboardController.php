<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
       
        // DATA GRAFIK RENTANG USIA
        $dataGrafikUsia = DB::table('years')
        ->selectRaw("
        CASE
            WHEN tahun_lahir BETWEEN 2020 AND 2025 THEN 'Usia 0-5 Tahun'
            WHEN tahun_lahir BETWEEN 2008 AND 2019 THEN 'Usia 6-17 Tahun'
            WHEN tahun_lahir BETWEEN 1966 AND 2007 THEN 'Usia 18-59 Tahun'
            ELSE 'Usia Lansia (60+ Tahun)'
        END as rentang_usia,
        SUM(jumlah) as jumlah
        ")
        ->groupBy('rentang_usia')
        ->orderBy('rentang_usia', 'asc')
        ->get();

        $labelsUsia = $dataGrafikUsia->pluck('rentang_usia');
        $dataUsia = $dataGrafikUsia->pluck('jumlah');

        // DATA GRAFIK STATUS PENDIDIKAN
        $dataGrafikPendidikan = DB::table('education')
            ->select('sekolah', 'jumlah')
            ->get();
        $labelsPendidikan = $dataGrafikPendidikan->pluck('sekolah');
        $dataPendidikan = $dataGrafikPendidikan->pluck('jumlah');

        // DATA GRAFIK STATUS PEKERJAAN
        $dataGrafikPekerjaan = DB::table('occupations')
            ->select('pekerjaan', 'jumlah')
            ->get();
        $labelsPekerjaan = $dataGrafikPekerjaan->pluck('pekerjaan');
        $dataPekerjaan = $dataGrafikPekerjaan->pluck('jumlah');

        // DATA GRAFIK STATUS KEPENDUDUKAN
        $dataGrafikKependudukan = DB::table('residents')
            ->select('status_tinggal', 'jumlah')
            ->get();
        $labelsKependudukan = $dataGrafikKependudukan->pluck('status_tinggal');
        $dataKependudukan = $dataGrafikKependudukan->pluck('jumlah');

        // =======================================================
        // DATA BARU UNTUK STAT CARD (KPI)
        // =======================================================
        // Ganti 'years' dengan nama tabel utama data warga Anda jika berbeda
        $totalWarga = DB::table('years')->count();

        // =======================================================
        // KIRIM SEMUA DATA KE VIEW
        // =======================================================
        return view('pages.dashboard', compact(
            'labelsUsia', 'dataUsia',
            'labelsPendidikan', 'dataPendidikan',
            'labelsPekerjaan', 'dataPekerjaan',
            'labelsKependudukan', 'dataKependudukan',
            'totalWarga' // <-- Variabel baru ditambahkan di sini
        ));
    }
}