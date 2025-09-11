<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // Penting untuk validasi massal

class PendudukController extends Controller
{
    /**
     * Menampilkan daftar data penduduk dengan filter hak akses, pencarian, dan ringkasan.
     */
    public function index(Request $request)
    {
        $loggedInUser = Auth::user();

        // --- 1. MENENTUKAN ID RT YANG BOLEH DIAKSES BERDASARKAN ROLE ---
        $accessibleRtIds = [];
        if ($loggedInUser->hasRole('KELURAHAN')) {
            $rwIds = User::where('parent_id', $loggedInUser->id)->pluck('id');
            $accessibleRtIds = User::whereIn('parent_id', $rwIds)->pluck('id')->toArray();
        } elseif ($loggedInUser->hasRole('RW')) {
            $accessibleRtIds = User::where('parent_id', $loggedInUser->id)->pluck('id')->toArray();
        } elseif ($loggedInUser->hasRole('RT')) {
            $accessibleRtIds = [$loggedInUser->id];
        }

        // --- 2. MEMBUAT QUERY BUILDER DASAR ---
        $query = Penduduk::query();

        // --- 3. MENERAPKAN FILTER HAK AKSES ---
        if (!empty($accessibleRtIds)) {
            $query->whereIn('user_id', $accessibleRtIds);
        }

        // --- 4. MENERAPKAN FILTER PENCARIAN DARI FORM ---
        $yearStart = $request->input('year_start');
        $yearEnd = $request->input('year_end');
        if ($yearStart) {
            $query->where('year', '>=', $yearStart);
        }
        if ($yearEnd) {
            $query->where('year', '<=', $yearEnd);
        }

        // --- 5. MELAKUKAN SEMUA PERHITUNGAN SETELAH DIFILTER ---
        $filteredQuery = clone $query;
        
        $total = $filteredQuery->count();
        $genderTotals = $filteredQuery->clone()->select('gender', DB::raw('count(*) as total'))->groupBy('gender')->pluck('total', 'gender');
        $residentTotals = $filteredQuery->clone()->select('resident', DB::raw('count(*) as total'))->groupBy('resident')->pluck('total', 'resident');
        $educationTotals = $filteredQuery->clone()->select('education', DB::raw('count(*) as total'))->groupBy('education')->pluck('total', 'education');
        $occupationTotals = $filteredQuery->clone()->select('occupation', DB::raw('count(*) as total'))->groupBy('occupation')->pluck('total', 'occupation');
        $lampidTotals = $filteredQuery->clone()->select('lampid', DB::raw('count(*) as total'))->whereNotNull('lampid')->groupBy('lampid')->pluck('total', 'lampid');
        
        $currentYear = date('Y');
        $ageGroupTotals = [
            'balita' => $filteredQuery->clone()->whereRaw("$currentYear - year BETWEEN 0 AND 5")->count(),
            'anak'   => $filteredQuery->clone()->whereRaw("$currentYear - year BETWEEN 6 AND 12")->count(),
            'remaja' => $filteredQuery->clone()->whereRaw("$currentYear - year BETWEEN 13 AND 18")->count(),
            'dewasa' => $filteredQuery->clone()->whereRaw("$currentYear - year BETWEEN 19 AND 55")->count(),
            'lansia' => $filteredQuery->clone()->whereRaw("$currentYear - year >= 56")->count(),
        ];

        // --- 6. AMBIL DATA UTAMA DENGAN PAGINATION ---
        $penduduks = $query->latest()->paginate(10)->appends($request->query());

        // --- 7. KIRIM SEMUA DATA KE VIEW ---
        return view('pages.penduduk.index', compact(
            'penduduks', 'total', 'yearStart', 'yearEnd', 'genderTotals', 'residentTotals',
            'educationTotals', 'occupationTotals', 'lampidTotals', 'ageGroupTotals'
        ));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('pages.penduduk.create');
    }

    /**
     * Menyimpan data baru (massal) ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year.*'        => 'required|numeric|digits:4',
            'gender.*'      => 'required|in:laki-laki,perempuan',
            'resident.*'    => 'required|in:tetap,pindahan',
            'religion.*'    => 'required|string|max:255',
            'education.*'   => 'required|in:belum sekolah,masih sekolah,putus sekolah',
            'occupation.*'  => 'required|in:bekerja,tidak bekerja,usaha',
            'lampid.*'      => 'nullable|in:kelahiran,kematian,pindah,datang',
        ]);

        $dataToInsert = [];
        $timestamp = now();
        $userId = Auth::id();

        foreach ($request->year as $key => $value) {
            $dataToInsert[] = [
                'user_id'    => $userId,
                'year'       => $request->year[$key],
                'gender'     => $request->gender[$key],
                'resident'   => $request->resident[$key],
                'religion'   => $request->religion[$key],
                'education'  => $request->education[$key],
                'occupation' => $request->occupation[$key],
                'lampid'     => $request->lampid[$key] ?? null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        if (!empty($dataToInsert)) {
            Penduduk::insert($dataToInsert);
            return redirect()->route('penduduk.index')
                             ->with('success', count($dataToInsert) . ' data penduduk berhasil ditambahkan.');
        }

        return redirect()->back()->withErrors(['msg' => 'Tidak ada data valid yang dapat disimpan.'])->withInput();
    }

    /**
     * Menampilkan form untuk mengubah data tunggal.
     */
    public function edit(Penduduk $penduduk)
    {
        return view('pages.penduduk.edit', compact('penduduk'));
    }

    /**
     * Memperbarui data tunggal di database.
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
        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    /**
     * Menghapus data tunggal dari database.
     */
    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil dihapus.');
    }
}

