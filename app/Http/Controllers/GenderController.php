<?php

namespace App\Http\Controllers;

use App\Models\Gender; // <-- Tambahkan ini
use App\Models\User;   // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use PDF;

class GenderController extends Controller
{
    public function __construct()
    {
        // Aturan 1: Semua peran ini bisa mengakses method 'index' dan 'printPDF' (melihat).
        $this->middleware('role:SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')->only(['index', 'printPDF']);
        
        // Aturan 2: HANYA peran ini (TERMASUK RT) yang bisa mengakses method lainnya (create, store, dll).
        $this->middleware('role:SUPERADMIN|KECAMATAN|RT')->except(['index', 'printPDF']);
    }

    public function index()
    {
        $user = Auth::user();
        $gendersQuery = Gender::with('user')->latest();

        if ($user->hasRole('RT')) {
            $gendersQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $gendersQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $gendersQuery->whereIn('user_id', $rtIds);
        }

        $genders = $gendersQuery->paginate(10);
        $total_jumlah = (clone $gendersQuery)->sum('jumlah');

        return view('pages.gender.index', compact('genders', 'total_jumlah'));
    }

    public function create()
    {
        return view('pages.gender.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:laki-laki,perempuan',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $request->user()->genders()->create($request->all());
        return redirect()->route('gender.index')->with('success', 'Data kependudukan berhasil ditambahkan.');
    }

    public function edit(Gender $gender)
    {
        return view('pages.gender.edit', compact('gender'));
    }

    public function update(Request $request, gender $gender)
    {
        $request->validate([
            'gender' => 'required|in:laki-laki,perempuan',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $gender->update($request->all());
        return redirect()->route('gender.index')->with('success', 'Data kependudukan berhasil diperbarui.');
    }

    public function destroy(Gender $gender)
    {
        $gender->delete();
        return redirect()->route('gender.index')->with('success', 'Data kependudukan berhasil dihapus.');
    }

    public function printPDF()
    {
        $user = Auth::user();
        $gendersQuery = Gender::with('user')->latest();

        if ($user->hasRole('RT')) {
            $gendersQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $gendersQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $gendersQuery->whereIn('user_id', $rtIds);
        }
        
        $genders = $gendersQuery->get();
        $total_jumlah = $genders->sum('jumlah');

        $pdf = PDF::loadView('pages.gender.cetak', compact('genders', 'total_jumlah'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-warga.pdf');
    }
}
