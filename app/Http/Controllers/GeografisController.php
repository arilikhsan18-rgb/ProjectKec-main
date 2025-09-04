<?php

namespace App\Http\Controllers;

use App\Models\Geografis; // <-- Tambahkan ini
use App\Models\User;   // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use PDF;

class GeografisController extends Controller
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
        $geografissQuery = Geografis::with('user')->latest();

        if ($user->hasRole('RT')) {
            $geografissQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $geografissQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $geografissQuery->whereIn('user_id', $rtIds);
        }

        $geografiss = $geografissQuery->paginate(10);

        return view('pages.geografis.index', compact('geografiss'));
    }

    public function create()
    {
        return view('pages.geografis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'geografis' => 'required|luas',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $request->user()->geografiss()->create($request->all());
        return redirect()->route('geografis.index')->with('success', 'Data kependudukan berhasil ditambahkan.');
    }

    public function edit(Geografis $geografis)
    {
        return view('pages.geografis.edit', compact('geografis'));
    }

    public function update(Request $request, geografis $geografis)
    {
        $request->validate([
            'geografis' => 'required|luas',
        ]);
        $geografis->update($request->all());
        return redirect()->route('geografis.index')->with('success', 'Data kependudukan berhasil diperbarui.');
    }

    public function destroy(Geografis $geografis)
    {
        $geografis->delete();
        return redirect()->route('geografis.index')->with('success', 'Data kependudukan berhasil dihapus.');
    }

    public function printPDF()
    {
        $user = Auth::user();
        $geografissQuery = Geografis::with('user')->latest();

        if ($user->hasRole('RT')) {
            $geografissQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $geografissQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $geografissQuery->whereIn('user_id', $rtIds);
        }
        
        $geografiss = $geografissQuery->get();

        $pdf = PDF::loadView('pages.geografis.cetak', compact('geografiss'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-warga.pdf');
    }
}
