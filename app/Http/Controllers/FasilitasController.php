<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Fasilitas::query()->with('user');

        if ($user->hasRole('RT')) {
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('RW')) {
            $accessibleRtIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            $query->whereIn('user_id', $accessibleRtIds);
        } elseif ($user->hasRole('KELURAHAN')) {
            $rwIds = User::where('parent_id', $user->id)->pluck('id');
            $accessibleRtIds = User::whereIn('parent_id', $rwIds)->pluck('id')->toArray();
            $query->whereIn('user_id', $accessibleRtIds);
        }

        $fasilitas = $query->latest()->paginate(10);
        return view('pages.fasilitas.index', compact('fasilitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.fasilitas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'jenis_fasilitas' => 'required|in:Tempat Ibadah,Pendidikan,Kesehatan,Umum',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);
        $validatedData['user_id'] = Auth::id();
        Fasilitas::create($validatedData);
        return redirect()->route('fasilitas.index')->with('success', 'Data fasilitas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fasilitas $fasilitas)
    {
        // --- PERUBAHAN DI SINI ---
        // Cek manual: apakah user adalah pemilik ATAU superadmin
        if (Auth::id() !== $fasilitas->user_id && !Auth::user()->hasRole('SUPERADMIN')) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK TINDAKAN INI.');
        }
        return view('pages.fasilitas.edit', compact('fasilitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fasilitas $fasilitas)
    {
        // --- PERUBAHAN DI SINI ---
        if (Auth::id() !== $fasilitas->user_id && !Auth::user()->hasRole('SUPERADMIN')) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK TINDAKAN INI.');
        }

        $validatedData = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'jenis_fasilitas' => 'required|in:Tempat Ibadah,Pendidikan,Kesehatan,Umum',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);
        $fasilitas->update($validatedData);
        return redirect()->route('fasilitas.index')->with('success', 'Data fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fasilitas $fasilitas)
    {
        // --- PERUBAHAN DI SINI ---
        if (Auth::id() !== $fasilitas->user_id && !Auth::user()->hasRole('SUPERADMIN')) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK TINDAKAN INI.');
        }
        
        $fasilitas->delete();
        return redirect()->route('fasilitas.index')->with('success', 'Data fasilitas berhasil dihapus.');
    }
}
