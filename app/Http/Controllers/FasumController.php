<?php

namespace App\Http\Controllers;

use App\Models\Fasum; // <-- Tambahkan ini
use App\Models\User;   // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use PDF;

class FasumController extends Controller
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
        $fasumsQuery = Fasum::with('user')->latest();

        if ($user->hasRole('RT')) {
            $fasumsQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $fasumsQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $fasumsQuery->whereIn('user_id', $rtIds);
        }

        $fasums = $fasumsQuery->paginate(10);
        $total_jumlah = (clone $fasumsQuery)->sum('jumlah');

        return view('pages.fasum.index', compact('fasums', 'total_jumlah'));
    }

    public function create()
    {
        return view('pages.fasum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required| max:100',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $request->user()->fasums()->create($request->all());
        return redirect()->route('fasum.index')->with('success', 'Data kependudukan berhasil ditambahkan.');
    }

    public function edit(Fasum $fasum)
    {
        return view('pages.fasum.edit', compact('fasum'));
    }

    public function update(Request $request, fasum $fasum)
    {
        $request->validate([
            'nama' => 'required| max:100',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $fasum->update($request->all());
        return redirect()->route('fasum.index')->with('success', 'Data kependudukan berhasil diperbarui.');
    }

    public function destroy(Fasum $fasum)
    {
        $fasum->delete();
        return redirect()->route('fasum.index')->with('success', 'Data kependudukan berhasil dihapus.');
    }

    public function printPDF()
    {
        $user = Auth::user();
        $fasumsQuery = Fasum::with('user')->latest();

        if ($user->hasRole('RT')) {
            $fasumsQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $fasumsQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $fasumsQuery->whereIn('user_id', $rtIds);
        }
        
        $fasums = $fasumsQuery->get();
        $total_jumlah = $fasums->sum('jumlah');

        $pdf = PDF::loadView('pages.fasum.cetak', compact('fasums', 'total_jumlah'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-warga.pdf');
    }
}
