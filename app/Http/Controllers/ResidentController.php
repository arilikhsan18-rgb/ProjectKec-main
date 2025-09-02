<?php

namespace App\Http\Controllers;

// ===== VVV PENTING: PASTIKAN BARIS INI ADA VVV =====
// Ini adalah "kotak peralatan" yang memberikan akses ke fungsi middleware().
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// =======================================================

// Models and Facades
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

// ===== VVV PENTING: PASTIKAN ADA 'extends Controller' VVV =====
class ResidentController extends Controller
{
    // ===== VVV PENTING: PASTIKAN BARIS INI ADA VVV =====
    // Ini memberitahu controller untuk menggunakan "kotak peralatan" di atas.
    use AuthorizesRequests;
    // ==================================================

    /**
     * Terapkan middleware hak akses saat controller ini dibuat.
     * Kode ini SEKARANG AKAN BERFUNGSI.
     */
    public function __construct()
    {
        // Aturan 1: Semua peran ini bisa mengakses method 'index' dan 'printPDF' (melihat).
        $this->middleware('role:SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')->only(['index', 'printPDF']);
        
        // Aturan 2: HANYA peran ini (TERMASUK RT) yang bisa mengakses method lainnya (create, store, dll).
        $this->middleware('role:SUPERADMIN|KECAMATAN|RT')->except(['index', 'printPDF']);
    }

    /**
     * Menampilkan daftar data kependudukan.
     */
    public function index()
    {
        $user = Auth::user();
        $residentsQuery = Resident::with('user')->latest();

        if ($user->hasRole('RT')) {
            $residentsQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $residentsQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $residentsQuery->whereIn('user_id', $rtIds);
        }

        $residents = $residentsQuery->paginate(10);
        $total_jumlah = (clone $residentsQuery)->sum('jumlah');

        return view('pages.resident.index', compact('residents', 'total_jumlah'));
    }

    // ... (SEMUA METHOD LAINNYA SEPERTI create, store, edit, update, destroy, printPDF TETAP SAMA) ...
    
    public function create()
    {
        return view('pages.resident.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_tinggal' => 'required|in:tetap,pindahan',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $request->user()->residents()->create($request->all());
        return redirect()->route('resident.index')->with('success', 'Data kependudukan berhasil ditambahkan.');
    }

    public function edit(Resident $resident)
    {
        return view('pages.resident.edit', compact('resident'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'status_tinggal' => 'required|in:tetap,pindahan',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $resident->update($request->all());
        return redirect()->route('resident.index')->with('success', 'Data kependudukan berhasil diperbarui.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('resident.index')->with('success', 'Data kependudukan berhasil dihapus.');
    }

    public function printPDF()
    {
        $user = Auth::user();
        $residentsQuery = Resident::with('user')->latest();

        if ($user->hasRole('RT')) {
            $residentsQuery->where('user_id', $user->id);
        }
        elseif ($user->hasRole('RW')) {
            $rtIds = User::where('parent_id', $user->id)->pluck('id');
            $residentsQuery->whereIn('user_id', $rtIds);
        }
        elseif ($user->hasRole('KELURAHAN')) {
             $rwIds = User::where('parent_id', $user->id)->pluck('id');
             $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
             $residentsQuery->whereIn('user_id', $rtIds);
        }
        
        $residents = $residentsQuery->get();
        $total_jumlah = $residents->sum('jumlah');

        $pdf = PDF::loadView('pages.resident.cetak', compact('residents', 'total_jumlah'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-warga.pdf');
    }
}

