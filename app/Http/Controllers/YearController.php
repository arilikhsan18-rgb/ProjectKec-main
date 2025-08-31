<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PDF;

class YearController extends Controller
{
    public function index()
    {
        $years = Year::all();

        $total_jumlah = Year::sum('jumlah');

        return view('pages.year.index', compact('years'));
    }

    public function create()
    {
        return view('pages.year.create');
    }

    public function store(Request $request)
    {
        

        $validatedData = $request->validate([
            'tahun_lahir'     => ['required', 'max:100'], 
            'jumlah'          => ['required','max:100'],
        ]);

        Year::create($validatedData);

        return redirect('/year')->with('sukses', 'Berhasil memasukkan data');
    }

    public function edit($id)
    {
        $year = Year::findOrFail($id);
        return view('pages.year.edit', [
            'year' => $year,
        ]);
    }

    public function update(Request $request, $id)
    {
            $validatedData = $request->validate([
            'tahun_lahir'     => ['required', 'max:100'], 
            'jumlah'          =>['required','max:100'],
        ]);

        Year::FindOrFail($id)->update($validatedData);

        return redirect('/year')->with('sukses', 'Berhasil mengubah data');
    }
    
    public function destroy($id)
    {
        $year = Year::findOrFail($id);
        $year->delete();

        return redirect('/year')->with('sukses', 'berhasil menghapus data');
    }
        public function printPDF()
{
    // Ambil data yang sama dengan yang Anda gunakan di halaman index
    $years = Year::all(); // Sesuaikan cara Anda mengambil data
    $total_jumlah = $years->sum('jumlah');

    // Muat view PDF dan kirimkan datanya
    $pdf = PDF::loadView('pages.year.cetak', [
        'years' => $years,
        'total_jumlah' => $total_jumlah
    ]);

    // (Opsional) Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Tampilkan PDF di browser
    return $pdf->stream('laporan-data-warga.pdf');
}
}
