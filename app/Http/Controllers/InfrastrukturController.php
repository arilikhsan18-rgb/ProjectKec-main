<?php

namespace App\Http\Controllers;

use App\Models\Infrastruktur;
use Illuminate\Http\Request;
// Tambahkan ini untuk menggunakan fitur Storage
use Illuminate\Support\Facades\Storage;
use PDF;

class InfrastrukturController extends Controller
{
    public function index()
    {
        $infrastrukturs = Infrastruktur::all();
        return view('pages.infrastruktur.index', [
            'infrastrukturs' => $infrastrukturs,
        ]);
    }

    public function create()
    {
        return view('pages.infrastruktur.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi dengan aturan yang lebih spesifik untuk gambar
        $validatedData = $request->validate([
            'gambar'     => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'alamat'     => ['required', 'max:700'],
            'ukuran'     => ['required', 'max:100'],
            'keterangan' => ['required', 'max:700'],
        ]);

        // 2. Simpan gambar ke storage dan ambil path-nya
        if ($request->file('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('public/gambar-infrastruktur');
            // Hapus 'public/' dari path untuk disimpan ke DB
            $validatedData['gambar'] = str_replace('public/', '', $validatedData['gambar']);
        }

        // 3. Simpan data ke database
        Infrastruktur::create($validatedData);

        return redirect('/infrastruktur')->with('sukses', 'Berhasil memasukkan data');
    }

    public function edit($id)
    {
        $infrastruktur = Infrastruktur::findOrFail($id);
        return view('pages.infrastruktur.edit', [
            'infrastruktur' => $infrastruktur,
        ]);
    }

    public function update(Request $request, $id)
    {
        $infrastruktur = Infrastruktur::findOrFail($id);

        // Aturan validasi, buat gambar opsional saat update
        $rules = [
            'alamat'     => ['required', 'max:700'],
            'ukuran'     => ['required', 'max:100'],
            'keterangan' => ['required', 'max:700'],
            'gambar'     => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        $validatedData = $request->validate($rules);

        // Cek jika ada file gambar baru yang di-upload
        if ($request->file('gambar')) {
            // Hapus gambar lama jika ada
            if ($infrastruktur->gambar) {
                Storage::delete('public/' . $infrastruktur->gambar);
            }
            // Simpan gambar baru dan update path di data validasi
            $path = $request->file('gambar')->store('public/gambar-infrastruktur');
            $validatedData['gambar'] = str_replace('public/', '', $path);
        }

        // Update data di database
        $infrastruktur->update($validatedData);

        return redirect('/infrastruktur')->with('sukses', 'Berhasil mengubah data');
    }
    
    public function destroy($id)
    {
        $infrastruktur = Infrastruktur::findOrFail($id);

        // Hapus file gambar dari storage sebelum menghapus data dari DB
        if ($infrastruktur->gambar) {
            Storage::delete('public/' . $infrastruktur->gambar);
        }

        // Hapus data dari database
        $infrastruktur->delete();

        return redirect('/infrastruktur')->with('sukses', 'Berhasil menghapus data');
    }

    public function printPDF()
{
    // Ambil data yang sama dengan yang Anda gunakan di halaman index
    $infrastrukturs = Infrastruktur::all(); // Sesuaikan cara Anda mengambil data
    $total_jumlah = $infrastrukturs->sum('jumlah');

    // Muat view PDF dan kirimkan datanya
    $pdf = PDF::loadView('pages.infrastruktur.cetak', [
        'infrastrukturs' => $infrastrukturs,
        'total_jumlah' => $total_jumlah
    ]);

    // (Opsional) Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Tampilkan PDF di browser
    return $pdf->stream('laporan-data-warga.pdf');
}

}