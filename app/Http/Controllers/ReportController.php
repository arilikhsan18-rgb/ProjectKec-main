<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Year;
use App\Models\Occupation;
use App\Models\Education;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan Anda sudah menginstall package barryvdh/laravel-dompdf

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan utama.
     */
    public function index()
    {
        // 1. Dapatkan daftar ID dari user RT yang datanya boleh diakses oleh user yang login.
        $authorizedRtIds = $this->getAuthorizedRtIds();

        // 2. Ambil data laporan yang sudah diolah, dengan filter berdasarkan ID RT yang diizinkan.
        $reportData = $this->getReportData($authorizedRtIds);

        // 3. Kirim data yang sudah difilter dan diolah ke view.
        return view('pages.report.index', $reportData);
    }

    /**
     * Membuat dan menampilkan laporan dalam format PDF.
     */
    public function printPDF()
    {
        // Logikanya sama persis dengan method index() untuk konsistensi data.
        $authorizedRtIds = $this->getAuthorizedRtIds();
        $reportData = $this->getReportData($authorizedRtIds);

        // Muat view PDF dengan data yang sudah disiapkan.
        $pdf = PDF::loadView('pages.report.cetak', $reportData);
        $pdf->setPaper('A4', 'portrait');

        // Tampilkan PDF di browser.
        return $pdf->stream('laporan-data-warga.pdf');
    }

    /**
     * Method untuk menentukan ID RT mana saja yang boleh diakses berdasarkan role.
     * INI ADALAH LOGIKA OTORISASI.
     *
     * @return array|null Mengembalikan array berisi ID RT, atau null jika boleh melihat semua.
     */
    private function getAuthorizedRtIds(): ?array
    {
        $user = auth()->user();

        // SUPERADMIN dan KECAMATAN boleh melihat semua data dari semua RT.
        // Kita kembalikan null untuk menandakan "tanpa filter".
        if ($user->hasAnyRole(['SUPERADMIN', 'KECAMATAN'])) {
            return null;
        }

        // RW hanya boleh melihat data dari RT yang berada di bawahnya.
        if ($user->hasRole('RW')) {
            return User::where('parent_id', $user->id)->pluck('id')->toArray();
        }

        // KELURAHAN boleh melihat data dari semua RT di bawah semua RW-nya.
        if ($user->hasRole('KELURAHAN')) {
            // Langkah 1: Cari semua RW di bawah kelurahan ini.
            $rwIds = User::where('parent_id', $user->id)->pluck('id');
            // Langkah 2: Cari semua RT di bawah semua RW tersebut.
            return User::whereIn('parent_id', $rwIds)->pluck('id')->toArray();
        }

        // Role lain (seperti RT itu sendiri) tidak boleh melihat laporan agregat ini.
        return []; // Mengembalikan array kosong.
    }


    /**
     * Method untuk mengambil dan memproses semua data laporan dari database.
     * INI ADALAH LOGIKA AGREGASI DATA.
     *
     * @param array|null $rtIds ID dari RT yang datanya boleh diambil. Jika null, ambil semua.
     * @return array
     */
    private function getReportData(?array $rtIds): array
    {
        // Jika $rtIds adalah array kosong (misal: RT yang login),
        // langsung kembalikan data kosong untuk efisiensi, tanpa query ke database.
        if (is_array($rtIds) && empty($rtIds)) {
            return [
                'residents' => collect(), 'total_residents' => 0,
                'years' => collect(), 'total_years' => 0,
                'occupations' => collect(), 'total_occupations' => 0,
                'educations' => collect(), 'total_educations' => 0,
            ];
        }

        // Ini adalah "Closure" atau fungsi kecil untuk menerapkan filter hak akses
        // ke setiap query kita nanti, agar kode tidak berulang (DRY Principle).
        $applyAuthFilter = function ($query) use ($rtIds) {
            // Hanya terapkan filter 'whereIn' jika $rtIds BUKAN null.
            // Jika null (untuk Superadmin/Kecamatan), filter ini akan dilewati.
            if ($rtIds !== null) {
                $query->whereIn('user_id', $rtIds);
            }
        };

        // 1. Data Status Kependudukan (sudah difilter)
        $residents = Resident::query()
            ->select('status_tinggal', DB::raw('COUNT(*) as jumlah'))
            ->tap($applyAuthFilter) // Terapkan filter hak akses
            ->groupBy('status_tinggal')
            ->get();
        $total_residents = $residents->sum('jumlah');

        // 2. Data Tahun Kelahiran (sudah difilter)
        $years = Year::query()
            ->select('tahun_lahir', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('tahun_lahir')
            ->tap($applyAuthFilter) // Terapkan filter hak akses
            ->groupBy('tahun_lahir')
            ->orderBy('tahun_lahir', 'desc')
            ->get();
        $total_years = $years->sum('jumlah');

        // 3. Data Pekerjaan (sudah difilter)
        $occupations = Occupation::query()
            ->select('pekerjaan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('pekerjaan')->where('pekerjaan', '!=', '')
            ->tap($applyAuthFilter) // Terapkan filter hak akses
            ->groupBy('pekerjaan')
            ->get();
        $total_occupations = $occupations->sum('jumlah');

        // 4. Data Pendidikan (sudah difilter)
        $educations = Education::query()
            ->select('sekolah', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sekolah')->where('sekolah', '!=', '')
            ->tap($applyAuthFilter) // Terapkan filter hak akses
            ->groupBy('sekolah')
            ->get();
        $total_educations = $educations->sum('jumlah');

        // Kembalikan semua data dalam bentuk array agar mudah dikirim ke view
        return [
            'residents' => $residents, 'total_residents' => $total_residents,
            'years' => $years, 'total_years' => $total_years,
            'occupations' => $occupations, 'total_occupations' => $total_occupations,
            'educations' => $educations, 'total_educations' => $total_educations,
        ];
    }
}

