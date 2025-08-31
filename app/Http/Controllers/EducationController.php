<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PDF;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::all();

        $total_jumlah = Education::sum('jumlah');

        return view('pages.education.index', compact('educations', 'total_jumlah'));
    }

    public function create()
    {
        return view('pages.education.create');
    }

    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'sekolah'     => ['required', Rule::in(['masih sekolah','tidak sekolah','putus sekolah'])], 
            'jumlah'          => ['required','max:100'],
        ]);

        Education::create($validatedData);

        return redirect('/education')->with('sukses', 'Berhasil memasukkan data');
    }

    public function edit($id)
    {
        $education = Education::findOrFail($id);
        return view('pages.education.edit', [
            'education' => $education,
        ]);
    }

    public function update(Request $request, $id)
    {
            $validatedData = $request->validate([
            'sekolah'         =>['required', Rule::in(['masih sekolah','tidak sekolah','putus sekolah'])],  
            'jumlah'          =>['required','max:100'],
        ]);

        Education::FindOrFail($id)->update($validatedData);

        return redirect('/education')->with('sukses', 'Berhasil mengubah data');
    }
    
    public function destroy($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return redirect('/education')->with('sukses', 'berhasil menghapus data');
    }
    public function printPDF()
{
    // Ambil data yang sama dengan yang Anda gunakan di halaman index
    $educations = Education::all(); // Sesuaikan cara Anda mengambil data
    $total_jumlah = $educations->sum('jumlah');

    // Muat view PDF dan kirimkan datanya
    $pdf = PDF::loadView('pages.education.cetak', [
        'educations' => $educations,
        'total_jumlah' => $total_jumlah
    ]);

    // (Opsional) Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Tampilkan PDF di browser
    return $pdf->stream('laporan-data-warga.pdf');
}

}