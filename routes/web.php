<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\LampidController;
use App\Http\Controllers\GeografisController;
use App\Http\Controllers\FasumController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\SidakepController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\InfrastrukturController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Route untuk Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});




// Grup untuk semua yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('/sidakep', [SidakepController::class, 'index'])->name('sidakep');
    

    // Daftarkan rute kustom (seperti cetak) SEBELUM Route::resource
    Route::get('/resident/cetak', [ResidentController::class, 'printPDF'])->name('resident.cetak');
    Route::get('/year/cetak', [YearController::class, 'printPDF'])->name('year.cetak');
    Route::get('/education/cetak', [EducationController::class, 'printPDF'])->name('education.cetak');
    Route::get('/occupation/cetak', [OccupationController::class, 'printPDF'])->name('occupation.cetak');

    Route::get('/infrastruktur/cetak', [InfrastrukturController::class, 'printPDF'])->name('infrastruktur.cetak');
    Route::get('/room/cetak', [RoomController::class, 'printPDF'])->name('room.cetak');

    // --DATA LAIN LAIN
    Route::middleware(['auth'])->group(function () {
    // Route baru untuk halaman filter tahun
    Route::get('penduduk/year', [PendudukController::class, 'year'])->name('penduduk.year');
    
    // Route resource Anda yang sudah ada
    Route::resource('penduduk', PendudukController::class);
    });


    // --- DATA  PROFIL (Hak akses diatur di dalam Controller) ---
    Route::resource('resident', ResidentController::class);
    Route::resource('year', YearController::class);
    Route::resource('education', EducationController::class);
    Route::resource('occupation', OccupationController::class);
    Route::resource('gender', GenderController::class);
    Route::resource('lampid', LampidController::class);
    Route::resource('fasum', FasumController::class);
    Route::resource('geografis', GeografisController::class);
    Route::resource('penduduk', PendudukController::class);
    Route::resource('fasilitas', FasilitasController::class);

    // --- LINGKUNGAN & BARANG (Hak akses diatur di dalam Controller) ---
    Route::resource('infrastruktur', InfrastrukturController::class);
    Route::resource('room', RoomController::class);
    Route::resource('inventaris', InventarisController::class);
    Route::get('/inventaris/pdf', [InventarisController::class, 'exportPDF'])->name('inventaris.pdf');

    // --- AKUN (Hak akses diatur di dalam Controller) ---
    Route::resource('user', UserController::class);

    // --- LAPORAN (Hak akses diatur dengan middleware grup) ---
    Route::middleware('role:SUPERADMIN|KECAMATAN|KELURAHAN|RW')->group(function () {
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        // ===================================================================
        // VVV INI ADALAH PERBAIKANNYA VVV
        Route::get('/report/cetak', [ReportController::class, 'printPDF'])->name('report.cetak');
        // ===================================================================
    });
});
