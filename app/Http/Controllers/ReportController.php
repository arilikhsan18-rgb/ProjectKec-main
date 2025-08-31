<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Year;
use App\Models\Occupation;
use App\Models\Education; // Cukup gunakan model Resident sebagai sumber data utama
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan utama.
     */
    public function index()
    {
        // Panggil method private untuk mengambil semua data laporan
        $reportData = $this->getReportData();

        // Kirim data yang sudah diambil ke view
        return view('pages.report.index', $reportData);
    }

    /**
     * Membuat dan menampilkan laporan dalam format PDF.
     */
    public function printPDF()
    {
        // Panggil method yang sama untuk memastikan data di PDF konsisten
        $reportData = $this->getReportData();

        // Muat view PDF dengan data yang sudah disiapkan
        $pdf = PDF::loadView('pages.report.cetak', $reportData);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-warga.pdf');
    }

    /**
     * Method private untuk mengambil dan memproses semua data laporan.
     * Ini untuk menghindari duplikasi kode (Prinsip DRY - Don't Repeat Yourself).
     *
     * @return array
     */
    private function getReportData(): array
    {
        // 1. Data Status Kependudukan
        // SEMUA query sekarang berasal dari model Resident
        $residents = Resident::query()
            ->select('status_tinggal', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status_tinggal')
            ->get();
        $total_residents = $residents->sum('jumlah');

        // 2. Data Tahun Kelahiran
        // Diasumsikan Anda memiliki kolom 'tanggal_lahir' di tabel warga
        $years = Year::query()
        ->select(DB::raw('YEAR(tahun_lahir) as tahun_lahir'), DB::raw('COUNT(*) as jumlah')) // Ganti alias agar tidak ambigu
        ->whereNotNull('tahun_lahir')
        ->groupBy('tahun_lahir') // Group by dengan alias baru
        ->orderBy('tahun_lahir', 'desc') // Order by dengan alias baru
        ->get();

        $total_years = $years->sum('jumlah');
        // 3. Data Pekerjaan
        $occupations = Occupation::query()
            ->select('pekerjaan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->groupBy('pekerjaan')
            ->get();
        $total_occupations = $occupations->sum('jumlah');

        // 4. Data Pendidikan
        $educations = Education::query()
            ->select('sekolah', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sekolah')
            ->where('sekolah', '!=', '')
            ->groupBy('sekolah')
            ->get();
        $total_educations = $educations->sum('jumlah');

        // Kembalikan semua data dalam bentuk array agar mudah dikelola
        return [
            'residents'         => $residents,
            'total_residents'   => $total_residents,
            'years'             => $years,
            'total_years'       => $total_years,
            'occupations'       => $occupations,
            'total_occupations' => $total_occupations,
            'educations'        => $educations,
            'total_educations'  => $total_educations,
        ];
    }
}
