<?php

namespace App\Http\Controllers;

use App\Models\Lampid; // <-- Tambahkan ini
use App\Models\User;   // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use PDF;

class LampidController extends Controller
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
        $lampidsQuery = Lampid::with('user')->latest();

        if ($user->hasRole('RT')) {
            $lampidsQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $lampidsQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $lampidsQuery->whereIn('user_id', $rtIds);
        }

        $lampids = $lampidsQuery->paginate(10);
        $total_jumlah = (clone $lampidsQuery)->sum('jumlah');

        return view('pages.lampid.index', compact('lampids', 'total_jumlah'));
    }

    public function create()
    {
        return view('pages.lampid.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:kelahiran,kematian,pindah,baru',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $request->user()->lampids()->create($request->all());
        return redirect()->route('lampid.index')->with('success', 'Data kependudukan berhasil ditambahkan.');
    }

    public function edit(Lampid $lampid)
    {
        return view('pages.lampid.edit', compact('lampid'));
    }

    public function update(Request $request, Lampid $lampid)
    {
        $request->validate([
            'status' => 'required|in:kelahiran,kematian,pindah,baru',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $lampid->update($request->all());
        return redirect()->route('lampid.index')->with('success', 'Data kependudukan berhasil diperbarui.');
    }

    public function destroy(Lampid $lampid)
    {
        $lampid->delete();
        return redirect()->route('lampid.index')->with('success', 'Data kependudukan berhasil dihapus.');
    }

    public function printPDF()
    {
        $user = Auth::user();
        $lampidsQuery = Lampid::with('user')->latest();

        if ($user->hasRole('RT')) {
            $lampidsQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $lampidsQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $lampidsQuery->whereIn('user_id', $rtIds);
        }
        
        $lampids = $lampidsQuery->get();
        $total_jumlah = $lampids->sum('jumlah');

        $pdf = PDF::loadView('pages.lampid.cetak', compact('lampids', 'total_jumlah'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-warga.pdf');
    }
}
