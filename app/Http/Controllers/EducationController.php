<?php

namespace App\Http\Controllers;

// ===== PERBAIKAN 1: TAMBAHKAN 'use' STATEMENT INI =====
// Ini adalah "kotak peralatan" yang memberikan akses ke fungsi middleware().
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// =======================================================

// Models and Facades
use App\Models\Education;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    // ===== PERBAIKAN 2: TAMBAHKAN TRAIT INI =====
    // Ini memberitahu controller untuk menggunakan "kotak peralatan" di atas.
    use AuthorizesRequests;
    // ===========================================

    /**
     * Terapkan middleware hak akses saat controller ini dibuat.
     * Kode ini SEKARANG AKAN BERFUNGSI.
     */
    public function __construct()
    {
        // Aturan 1: Semua peran ini bisa mengakses method 'index' (hanya untuk melihat daftar).
        $this->middleware('role:SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')->only('index');
        
        // Aturan 2: HANYA peran ini yang bisa mengakses SEMUA METHOD LAINNYA 
        // (create, store, edit, update, destroy).
        $this->middleware('role:SUPERADMIN|KECAMATAN|RT')->except('index');
    }

    // --- SEMUA KODE ANDA YANG LAIN DI BAWAH INI TIDAK BERUBAH ---
    // Logika fungsional Anda sudah benar dan dipertahankan.

    /**
     * Menampilkan daftar data status pendidikan.
     */
    // app/Http/Controllers/EducationController.php
    public function index()
    {
    $user = Auth::user();
    $educationsQuery = Education::with('user')->latest();

    if ($user->hasRole('RT')) {
        $educationsQuery->where('user_id', $user->id);
    }
    // ... (sisa filter if/elseif Anda tetap sama)
    elseif ($user->hasRole('RW')) {
        $rtIds = User::where('parent_id', $user->id)->pluck('id');
        $educationsQuery->whereIn('user_id', $rtIds);
    }
    elseif ($user->hasRole('KELURAHAN')) {
        $rwIds = User::where('parent_id', $user->id)->pluck('id');
        $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
        $educationsQuery->whereIn('user_id', $rtIds);
    }

    $educations = $educationsQuery->paginate(10);
    
    // Tambahkan baris ini
    $total_jumlah = (clone $educationsQuery)->sum('jumlah');
    return view('pages.education.index', compact('educations', 'total_jumlah'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('pages.education.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sekolah' => 'required|in:masih sekolah,tidak sekolah,putus sekolah',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $request->user()->educations()->create($request->all());

        return redirect()->route('education.index')
                         ->with('success', 'Data status pendidikan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(Education $education)
    {
        return view('pages.education.edit', compact('education'));
    }

    /**
     * Mengupdate data yang ada di database.
     */
    public function update(Request $request, Education $education)
    {
        $request->validate([
            'sekolah' => 'required|in:masih sekolah,tidak sekolah,putus sekolah',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $education->update($request->all());

        return redirect()->route('education.index')
                         ->with('success', 'Data status pendidikan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(Education $education)
    {
        $education->delete();

        return redirect()->route('education.index')
                         ->with('success', 'Data status pendidikan berhasil dihapus.');
    }
}