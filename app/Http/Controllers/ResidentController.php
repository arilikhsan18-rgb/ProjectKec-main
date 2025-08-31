<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PDF; 

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::all();

        $total_jumlah = Resident::sum('jumlah'); // <-- INI BAGIAN PENTINGNYA

        return view('pages.resident.index', compact('residents', 'total_jumlah'));
    }

    public function create()
    {
        return view('pages.resident.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'status_tinggal'    => ['required', Rule::in(['tetap', 'pindahan'])],
            'jumlah'            => ['required','max:100'],
        ]);

        Resident::create($validatedData);

        return redirect('/resident')->with('sukses', 'Berhasil memasukkan data');
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);

        return view('pages.resident.edit', [
            'resident' => $resident,
        ]);
    }
    

    public function update(Request $request, $id)
    {
            $validatedData = $request->validate([
            'status_tinggal'    => ['required', Rule::in(['tetap', 'pindahan'])],
            'jumlah'            => ['required', 'max:100'],
        ]);

        Resident::FindOrFail($id)->update($validatedData);

        return redirect('/resident')->with('sukses', 'Berhasil mengubah data');
    }
    
    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        $resident->delete();

        return redirect('/resident')->with('sukses', 'berhasil menghapus data');
    }




    public function printPDF()
    {
    // Ambil data yang sama dengan yang Anda gunakan di halaman index
    $residents = Resident::all(); // Sesuaikan cara Anda mengambil data
    $total_jumlah = $residents->sum('jumlah');

    // Muat view PDF dan kirimkan datanya
    $pdf = PDF::loadView('pages.resident.cetak', [
        'residents' => $residents,
        'total_jumlah' => $total_jumlah
    ]);

    // (Opsional) Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Tampilkan PDF di browser
    return $pdf->stream('laporan-data-warga.pdf');
    }
}
