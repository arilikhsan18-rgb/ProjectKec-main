<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Year;
use App\Models\User;
use Illuminate\Http\Request; // <-- PASTIKAN INI DI-IMPORT
use Illuminate\Support\Facades\Auth;

class YearController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('role:SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')->only('index');
        $this->middleware('role:SUPERADMIN|KECAMATAN|RT')->except('index');
    }

    // Ubah method index menjadi seperti ini
    public function index(Request $request)
    {
        $user = Auth::user();
        // 1. Ambil dua input untuk rentang tahun
        $startYear = $request->input('start_year');
        $endYear = $request->input('end_year');

        $yearsQuery = Year::with('user')->latest();

    // Filter berdasarkan role (logika Anda yang sudah ada)
    if ($user->hasRole('RT')) {
        $yearsQuery->where('user_id', $user->id);
    } elseif ($user->hasRole('RW')) {
        $rtIds = User::where('parent_id', $user->id)->pluck('id');
        $yearsQuery->whereIn('user_id', $rtIds);
    } elseif ($user->hasRole('KELURAHAN')) {
        $rwIds = User::where('parent_id', $user->id)->pluck('id');
        $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
        $yearsQuery->whereIn('user_id', $rtIds);
    }
    
    // 2. Logika baru untuk filter rentang tahun
    // Jika ada input tahun awal, cari yang lebih besar atau sama dengan
    if ($startYear) {
        $yearsQuery->where('tahun_lahir', '>=', $startYear);
    }

    // Jika ada input tahun akhir, cari yang lebih kecil atau sama dengan
    if ($endYear) {
        $yearsQuery->where('tahun_lahir', '<=', $endYear);
    }
    
    // Hitung total jumlah dari data yang sudah difilter
    $total_jumlah = (clone $yearsQuery)->sum('jumlah');

    // 3. Paginate dan tambahkan kedua parameter ke link paginasi
    $years = $yearsQuery->paginate(10)->appends([
        'start_year' => $startYear,
        'end_year' => $endYear,
    ]);

    return view('pages.year.index', compact('years', 'total_jumlah'));
}
    
    public function create()
    {
        return view('pages.year.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_lahir' => 'required|numeric|digits:4',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $request->user()->years()->create($request->all());
        return redirect()->route('year.index')->with('success', 'Data tahun kelahiran berhasil ditambahkan.');
    }

    public function edit(Year $year)
    {
        return view('pages.year.edit', compact('year'));
    }

    public function update(Request $request, Year $year)
    {
        $request->validate([
            'tahun_lahir' => 'required|numeric|digits:4',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $year->update($request->all());
        return redirect()->route('year.index')->with('success', 'Data tahun kelahiran berhasil diperbarui.');
    }

    public function destroy(Year $year)
    {
        $year->delete();
        return redirect()->route('year.index')->with('success', 'Data tahun kelahiran berhasil dihapus.');
    }
}