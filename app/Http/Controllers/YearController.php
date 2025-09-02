<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Year;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YearController extends Controller
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
    $yearsQuery = Year::with('user')->latest();

    if ($user->hasRole('RT')) {
        $yearsQuery->where('user_id', $user->id);
    }
    elseif ($user->hasRole('RW')) {
        $rtIds = User::where('parent_id', $user->id)->pluck('id');
        $yearsQuery->whereIn('user_id', $rtIds);
    }
    elseif ($user->hasRole('KELURAHAN')) {
         $rwIds = User::where('parent_id', $user->id)->pluck('id');
         $rtIds = User::whereIn('parent_id', $rwIds)->pluck('id');
         $yearsQuery->whereIn('user_id', $rtIds);
    }

    $years = $yearsQuery->paginate(10);
    
    // =======================================================
    // VVV TAMBAHAN: Hitung total dan kirim ke view VVV
    $total_jumlah = (clone $yearsQuery)->sum('jumlah');
    return view('pages.year.index', compact('years', 'total_jumlah'));
    // =======================================================
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

