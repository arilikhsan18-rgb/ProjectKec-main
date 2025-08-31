<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
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
    Route::get('/register', [AuthController::class, 'registerView']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Grup untuk semua yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- GRUP MENU KEPENDUDUKAN (MANUAL) ---
    // Aturan: Semua peran terkait (termasuk RT) bisa MELIHAT data
    Route::middleware('role:SUPERADMIN,KECAMATAN,KELURAHAN,RW,RT')->group(function () {
        // Hanya menampilkan daftar tabel
        Route::get('/resident', [ResidentController::class, 'index'])->name('resident.index');
        Route::get('/year', [YearController::class, 'index'])->name('year.index');
        Route::get('/education', [EducationController::class, 'index'])->name('education.index');
        Route::get('/occupation', [OccupationController::class, 'index'])->name('occupation.index');
    });

    // Aturan: Hanya peran tertentu (termasuk RT) yang bisa MENGELOLA (CRUD) data kependudukan
    Route::middleware('role:SUPERADMIN,KECAMATAN,RT')->group(function () {
        // --- Resident CRUD ---
        Route::get('/resident/create', [ResidentController::class, 'create'])->name('resident.create'); // Form tambah
        Route::post('/resident', [ResidentController::class, 'store'])->name('resident.store'); // Simpan data baru
        Route::get('/resident/{resident}/edit', [ResidentController::class, 'edit'])->name('resident.edit'); // Form edit
        Route::put('/resident/{resident}', [ResidentController::class, 'update'])->name('resident.update'); // Update data
        Route::delete('/resident/{resident}', [ResidentController::class, 'destroy'])->name('resident.destroy'); // Hapus data
        Route::get('/resident/cetak', [ResidentController::class, 'printPDF'])->name('resident.cetak'); // Cetak
        
        // --- Year CRUD ---
        Route::get('/year/create', [YearController::class, 'create'])->name('year.create');
        Route::post('/year', [YearController::class, 'store'])->name('year.store');
        Route::get('/year/{year}/edit', [YearController::class, 'edit'])->name('year.edit');
        Route::put('/year/{year}', [YearController::class, 'update'])->name('year.update');
        Route::delete('/year/{year}', [YearController::class, 'destroy'])->name('year.destroy');
        Route::get('/year/cetak', [YearController::class, 'printPDF'])->name('year.cetak');

        // --- Education CRUD ---
        Route::get('/education/create', [EducationController::class, 'create'])->name('education.create');
        Route::post('/education', [EducationController::class, 'store'])->name('education.store');
        Route::get('/education/{education}/edit', [EducationController::class, 'edit'])->name('education.edit');
        Route::put('/education/{education}', [EducationController::class, 'update'])->name('education.update');
        Route::delete('/education/{education}', [EducationController::class, 'destroy'])->name('education.destroy');
        Route::get('/education/cetak', [EducationController::class, 'printPDF'])->name('education.cetak');

        // --- Occupation CRUD ---
        Route::get('/occupation/create', [OccupationController::class, 'create'])->name('occupation.create');
        Route::post('/occupation', [OccupationController::class, 'store'])->name('occupation.store');
        Route::get('/occupation/{occupation}/edit', [OccupationController::class, 'edit'])->name('occupation.edit');
        Route::put('/occupation/{occupation}', [OccupationController::class, 'update'])->name('occupation.update');
        Route::delete('/occupation/{occupation}', [OccupationController::class, 'destroy'])->name('occupation.destroy');
        Route::get('/occupation/cetak', [OccupationController::class, 'printPDF'])->name('occupation.cetak');
    });

    // --- GRUP MENU LINGKUNGAN & BARANG (MANUAL) ---
    // Aturan: KELURAHAN ke atas bisa MELIHAT data
    Route::middleware('role:SUPERADMIN,KECAMATAN,KELURAHAN')->group(function () {
        // Hanya menampilkan daftar tabel
        Route::get('/infrastruktur', [InfrastrukturController::class, 'index'])->name('infrastruktur.index');
        Route::get('/room', [RoomController::class, 'index'])->name('room.index');
        Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    });

    // Aturan: Hanya KECAMATAN ke atas yang bisa MENGELOLA (CRUD) data lingkungan
    Route::middleware('role:SUPERADMIN,KECAMATAN')->group(function () {
        // --- Infrastruktur CRUD ---
        Route::get('/infrastruktur/create', [InfrastrukturController::class, 'create'])->name('infrastruktur.create');
        Route::post('/infrastruktur', [InfrastrukturController::class, 'store'])->name('infrastruktur.store');
        Route::get('/infrastruktur/{infrastruktur}/edit', [InfrastrukturController::class, 'edit'])->name('infrastruktur.edit');
        Route::put('/infrastruktur/{infrastruktur}', [InfrastrukturController::class, 'update'])->name('infrastruktur.update');
        Route::delete('/infrastruktur/{infrastruktur}', [InfrastrukturController::class, 'destroy'])->name('infrastruktur.destroy');
        Route::get('/infrastruktur/cetak', [InfrastrukturController::class, 'printPDF'])->name('infrastruktur.cetak');
        
        // --- Room CRUD ---
        Route::get('/room/create', [RoomController::class, 'create'])->name('room.create');
        Route::post('/room', [RoomController::class, 'store'])->name('room.store');
        Route::get('/room/{room}/edit', [RoomController::class, 'edit'])->name('room.edit');
        Route::put('/room/{room}', [RoomController::class, 'update'])->name('room.update');
        Route::delete('/room/{room}', [RoomController::class, 'destroy'])->name('room.destroy');
        Route::get('/room/cetak', [RoomController::class, 'printPDF'])->name('room.cetak');

        // --- Inventaris CRUD ---
        Route::get('/inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
        Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
        Route::get('/inventaris/{inventari}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
        Route::put('/inventaris/{inventari}', [InventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{inventari}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');
        Route::get('/inventaris/pdf', [InventarisController::class, 'exportPDF'])->name('inventaris.pdf');
    });


    // --- GRUP MENU AKUN (MANUAL, Hanya SUPERADMIN) ---
    Route::middleware('role:SUPERADMIN')->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        // Rute 'show' untuk user juga dihapus untuk konsistensi
        Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('/user/cetak', [UserController::class, 'printPDF'])->name('user.cetak');
    });

    // --- GRUP LAPORAN (Semua bisa akses) ---
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/cetak', [ReportController::class, 'printPDF'])->name('report.cetak');
});

