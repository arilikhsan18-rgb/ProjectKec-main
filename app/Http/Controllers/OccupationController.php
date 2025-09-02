<?php

namespace App\Http\Controllers;

// ===== PERBAIKAN 1: TAMBAHKAN 'use' STATEMENT INI =====
// Ini adalah "kotak peralatan" yang memberikan akses ke fungsi middleware().
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// =======================================================

// Models and Facades
use App\Models\Occupation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OccupationController extends Controller
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
     * Menampilkan daftar data status pekerjaan.
     */
    // app/Http/Controllers/OccupationController.php
    public function index()
    {
    $user = Auth::user();
    $occupationsQuery = Occupation::with('user')->latest();

    if ($user->hasRole('RT')) {
        $occupationsQuery->where('user_id', $user->id);
    }
    // ... (sisa filter if/elseif Anda tetap sama)
    elseif ($user->hasRole('RW')) {
        $rtIds = User::where('parent_id', $user->id)->pluck('id');
        $occupationsQuery->whereIn('user_id', $rtIds);
    }
    elseif ($user->hasRole('KELURAHAN')) {
        $rwIds = User::where('parent_id', $user->id)->pluck('id');
        $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
        $occupationsQuery->whereIn('user_id', $rtIds);
    }

    $occupations = $occupationsQuery->paginate(10);
    
    // Tambahkan baris ini
    $total_jumlah = (clone $occupationsQuery)->sum('jumlah');
    return view('pages.occupation.index', compact('occupations', 'total_jumlah'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('pages.occupation.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pekerjaan' => 'required|in:bekerja,tidak bekerja,usaha',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $request->user()->occupations()->create($request->all());

        return redirect()->route('occupation.index')
                         ->with('success', 'Data status pekerjaan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(Occupation $occupation)
    {
        return view('pages.occupation.edit', compact('occupation'));
    }

    /**
     * Mengupdate data yang ada di database.
     */
    public function update(Request $request, Occupation $occupation)
    {
        $request->validate([
            'pekerjaan' => 'required|in:bekerja,tidak bekerja,usaha',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $occupation->update($request->all());

        return redirect()->route('occupation.index')
                         ->with('success', 'Data status pekerjaan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(Occupation $occupation)
    {
        $occupation->delete();

        return redirect()->route('occupation.index')
                         ->with('success', 'Data status pekerjaan berhasil dihapus.');
    }
}

