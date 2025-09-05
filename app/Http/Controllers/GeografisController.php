<?php

namespace App\Http\Controllers;

use App\Models\Geografis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GeografisController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('role:SUPERADMIN|KECAMATAN|KELURAHAN|RW|RT')->only('index');
        $this->middleware('role:SUPERADMIN|KECAMATAN|RT')->except('index');
    }

    public function index()
    {
        $user = Auth::user();
        $geografisQuery = Geografis::with('user');

        if ($user->hasRole('RT')) {
            $geografisQuery->where('user_id', $user->id);
        }

        $geografis = $geografisQuery->paginate(10);
        return view('pages.geografis.index', compact('geografis'));
    }

    public function create()
    {
        return view('pages.geografis.create', [
            'geografis' => new Geografis(),
        ]);
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'luas_wilayah' => 'required|string|max:255',
            'batas_wilayah_utara' => 'nullable|string|max:255',
            'batas_wilayah_selatan' => 'nullable|string|max:255',
            'batas_wilayah_barat' => 'nullable|string|max:255',
            'batas_wilayah_timur' => 'nullable|string|max:255',
        ]);

        // =======================================================
        // VVV INI ADALAH PERBAIKANNYA VVV
        // Tambahkan ID user yang sedang login ke dalam data yang akan disimpan
        $validatedData['user_id'] = Auth::id();
        // =======================================================

        Geografis::create($validatedData);

        return redirect()->route('geografis.index')
                         ->with('success', 'Data geografis berhasil disimpan.');
    }

    public function edit(Geografis $geografi)
    {
        return view('pages.geografis.edit', [
            'geografis' => $geografi,
        ]);
    }

    public function update(Request $request, Geografis $geografi)
    {
        $validatedData = $request->validate([
            'luas_wilayah' => 'required|string|max:255',
            'batas_wilayah_utara' => 'nullable|string|max:255',
            'batas_wilayah_selatan' => 'nullable|string|max:255',
            'batas_wilayah_barat' => 'nullable|string|max:255',
            'batas_wilayah_timur' => 'nullable|string|max:255',
        ]);

        $geografi->update($validatedData);

        return redirect()->route('geografis.index')
                         ->with('success', 'Data geografis berhasil diperbarui.');
    }

    public function destroy(Geografis $geografi)
    {
        $geografi->delete();
        return redirect()->route('geografis.index')
                         ->with('success', 'Data geografis berhasil dihapus.');
    }
}