<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use PDF; // Pastikan Anda sudah menginstall library PDF, misalnya barryvdh/laravel-dompdf

class RoomController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan.
     */
    public function index()
    {
        // Ambil semua data dari model Room
        $rooms = Room::all();

        // [INI PERBAIKANNYA]
        // Kirim (pass) variabel $rooms ke view agar bisa digunakan di file Blade.
        // Tanpa ini, variabel $rooms akan menjadi 'null' di dalam view.
        return view('pages.room.index', [
            'rooms' => $rooms
        ]);
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     */
    public function create()
    {
        return view('pages.room.create');
    }

    /**
     * Menyimpan data ruangan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'code' => ['required', 'max:100'],
        ]);

        Room::create($validatedData);

        return redirect('/room')->with('sukses', 'Berhasil memasukkan data');
    }

    /**
     * Menampilkan form untuk mengedit data ruangan.
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);

        return view('pages.room.edit', [
            'room' => $room,
        ]);
    }
    
    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'code' => ['required', 'max:100'],
        ]);

        Room::findOrFail($id)->update($validatedData);

        return redirect('/room')->with('sukses', 'Berhasil mengubah data');
    }
    
    /**
     * Menghapus data ruangan dari database.
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect('/room')->with('sukses', 'Berhasil menghapus data');
    }

    /**
     * Membuat dan menampilkan laporan dalam format PDF.
     */
    public function printPDF()
    {
        // Ambil data yang akan dicetak
        $rooms = Room::all(); 
        
        // Note: Pastikan model 'Room' Anda memiliki kolom bernama 'jumlah'
        // Jika tidak, baris ini mungkin menyebabkan error atau hasilnya 0.
        $total_jumlah = $rooms->sum('jumlah');

        // Muat view PDF dan kirimkan datanya
        $pdf = PDF::loadView('pages.room.cetak', [
            'rooms' => $rooms,
            'total_jumlah' => $total_jumlah
        ]);

        // Atur ukuran kertas dan orientasi
        $pdf->setPaper('A4', 'portrait');

        // Tampilkan PDF di browser untuk di-download atau dilihat
        return $pdf->stream('laporan-data-ruangan.pdf');
    }
}