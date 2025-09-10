<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendudukController extends Controller
{
    /**
     * Menampilkan daftar semua data penduduk dengan pagination, pencarian, dan ringkasan total.
     */
    public function index(Request $request)
    {
        $yearStart = $request->input('year_start');
        $yearEnd = $request->input('year_end');

        $query = Penduduk::query();

        if ($yearStart) {
            $query->where('year', '>=', $yearStart);
        }
        if ($yearEnd) {
            $query->where('year', '<=', $yearEnd);
        }

        // --- MULAI PERHITUNGAN TOTAL ---
        $filteredQuery = clone $query;
        
        $genderTotals = $filteredQuery->clone()->select('gender', DB::raw('count(*) as total'))->groupBy('gender')->pluck('total', 'gender');
        $residentTotals = $filteredQuery->clone()->select('resident', DB::raw('count(*) as total'))->groupBy('resident')->pluck('total', 'resident');
        $educationTotals = $filteredQuery->clone()->select('education', DB::raw('count(*) as total'))->groupBy('education')->pluck('total', 'education');
        $occupationTotals = $filteredQuery->clone()->select('occupation', DB::raw('count(*) as total'))->groupBy('occupation')->pluck('total', 'occupation');
        $lampidTotals = $filteredQuery->clone()->select('lampid', DB::raw('count(*) as total'))->whereNotNull('lampid')->groupBy('lampid')->pluck('total', 'lampid');
        
        // --- TAMBAHAN: PERHITUNGAN KELOMPOK USIA ---
        $currentYear = now()->year;
        $ageGroupTotals = [
            'balita' => $filteredQuery->clone()->where('year', '>=', $currentYear - 5)->count(), // 0-5 tahun
            'anak' => $filteredQuery->clone()->whereBetween('year', [$currentYear - 12, $currentYear - 6])->count(), // 6-12 tahun
            'remaja' => $filteredQuery->clone()->whereBetween('year', [$currentYear - 18, $currentYear - 13])->count(), // 13-18 tahun
            'dewasa' => $filteredQuery->clone()->whereBetween('year', [$currentYear - 55, $currentYear - 19])->count(), // 19-55 tahun
            'lansia' => $filteredQuery->clone()->where('year', '<=', $currentYear - 56)->count(), // 56+ tahun
        ];
        // --- SELESAI PERHITUNGAN ---
        
        $total = $query->count();
        $penduduks = $query->latest()->paginate(10)->appends($request->query());

        return view('pages.penduduk.index', compact(
            'penduduks', 
            'total', 
            'yearStart', 
            'yearEnd',
            'genderTotals',
            'residentTotals',
            'educationTotals',
            'occupationTotals',
            'lampidTotals',
            'ageGroupTotals' // Mengirim data kelompok usia ke view
        ));
    }

    /**
     * Menampilkan halaman untuk filter berdasarkan tahun.
     */
    public function year()
    {
        $years = Penduduk::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $selectedYear = request()->get('year_filter');
        $penduduks = collect(); 
        
        if ($selectedYear) {
            $penduduks = Penduduk::where('year', $selectedYear)
                                 ->latest()
                                 ->paginate(10)
                                 ->appends(['year_filter' => $selectedYear]);
        }

        return view('penduduk.year', compact('years', 'penduduks', 'selectedYear'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('pages.penduduk.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year' => 'required|numeric|digits:4',
            'gender' => 'required|in:laki-laki,perempuan',
            'resident' => 'required|in:tetap,pindahan',
            'religion' => 'required|string|max:255',
            'education' => 'required|in:belum sekolah,masih sekolah,putus sekolah',
            'occupation' => 'required|in:bekerja,tidak bekerja,usaha',
            'lampid' => 'nullable|in:kelahiran,kematian,pindah,datang',
        ]);

        $validatedData['user_id'] = Auth::id();

        Penduduk::create($validatedData);

        return redirect()->route('penduduk.index')
                         ->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengubah data.
     */
    public function edit(Penduduk $penduduk)
    {
        return view('pages.penduduk.edit', compact('penduduk'));
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, Penduduk $penduduk)
    {
        $validatedData = $request->validate([
            'year' => 'required|numeric|digits:4',
            'gender' => 'required|in:laki-laki,perempuan',
            'resident' => 'required|in:tetap,pindahan',
            'religion' => 'required|string|max:255',
            'education' => 'required|in:belum sekolah,masih sekolah,putus sekolah',
            'occupation' => 'required|in:bekerja,tidak bekerja,usaha',
            'lampid' => 'nullable|in:kelahiran,kematian,pindah,datang',
        ]);

        $penduduk->update($validatedData);

        return redirect()->route('penduduk.index')
                         ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();

        return redirect()->route('penduduk.index')
                         ->with('success', 'Data penduduk berhasil dihapus.');
    }
}

