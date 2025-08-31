<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PDF;

class OccupationController extends Controller
{
    public function index()
    {
        $occupations = Occupation::all();

        $total_jumlah = Occupation::sum('jumlah'); // <-- INI BAGIAN PENTINGNYA

        return view('pages.occupation.index', compact('occupations', 'total_jumlah'));
    }

    public function create()
    {
        return view('pages.occupation.create');
    }

    public function store(Request $request)
    {
        

        $validatedData = $request->validate([
            'pekerjaan'     => ['required', Rule::in('bekerja', 'tidak bekerja', 'usaha')], 
            'jumlah'          => ['required','max:100'],
        ]);

        Occupation::create($validatedData);

        return redirect('/occupation')->with('sukses', 'Berhasil memasukkan data');
    }

    public function edit($id)
    {
        $occupation = Occupation::findOrFail($id);
        return view('pages.occupation.edit', [
            'occupation' => $occupation,
        ]);
    }

    public function update(Request $request, $id)
    {
            $validatedData = $request->validate([
            'pekerjaan'     => ['required', Rule::in('bekerja', 'tidak bekerja', 'usaha')], 
            'jumlah'          =>['required','max:100'],
        ]);

        Occupation::FindOrFail($id)->update($validatedData);

        return redirect('/occupation')->with('sukses', 'Berhasil mengubah data');
    }
    
    public function destroy($id)
    {
        $occupation = Occupation::findOrFail($id);
        $occupation->delete();

        return redirect('/occupation')->with('sukses', 'berhasil menghapus data');
    }

    public function printPDF()
{
    // Ambil data yang sama dengan yang Anda gunakan di halaman index
    $occupations = Occupation::all(); // Sesuaikan cara Anda mengambil data
    $total_jumlah = $occupations->sum('jumlah');

    // Muat view PDF dan kirimkan datanya
    $pdf = PDF::loadView('pages.occupation.cetak', [
        'occupations' => $occupations,
        'total_jumlah' => $total_jumlah
    ]);

    // (Opsional) Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Tampilkan PDF di browser
    return $pdf->stream('laporan-data-warga.pdf');
}
}
