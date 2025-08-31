<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Room; // Pastikan Anda punya model Room
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua ruangan untuk ditampilkan di sidebar
        $rooms = Room::orderBy('name')->get();

        // Query dasar untuk inventaris
        $query = Inventaris::with('room'); // Eager load relasi room

        // Jika ada filter room_id dari URL (?room_id=xx)
        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        // Ambil data inventaris yang sudah difilter
        $inventaris = $query->latest()->get();
        $selectedRoom = Room::find($request->room_id);

        // Kirim data ke view
        return view('pages.inventaris.index', [
            'inventaris' => $inventaris,
            'rooms' => $rooms,
            'selectedRoom' => $selectedRoom,
        ]);
    }

    /**
     * Menampilkan halaman untuk membuat data inventaris baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Hanya menampilkan view 'create'
        return view('pages.inventaris.create');
    }

    /**
     * Menyimpan data inventaris baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:255|unique:inventaris,kode_barang',
            'merk_model' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tahun_pembelian' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'jumlah' => 'required|integer|min:0',
            'harga_perolehan' => 'required|numeric|min:0',
            'kondisi' => 'required|in:B,KB,RB',
            'keterangan' => 'nullable|string',
        ]);

        // Jika validasi gagal, kembali ke form dengan error dan input lama
        if ($validator->fails()) {
            return redirect()->route('inventaris.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Jika validasi berhasil, buat data baru
        Inventaris::create($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('inventaris.index')
                       ->with('success', 'Data inventaris berhasil ditambahkan.');
    }


    /**
     * Menampilkan halaman untuk mengedit data inventaris.
     *
     * @param  \App\Models\Inventaris  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventaris $item)
    {
        // Mengirim data item yang akan diedit ke view 'edit'
        // Laravel secara otomatis akan menemukan 'Inventaris' berdasarkan ID dari route model binding
        return view('inventaris.edit', ['item' => $item]);
    }

    /**
     * Memperbarui data inventaris di dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventaris  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventaris $item)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            // Pastikan kode barang unik, kecuali untuk data itu sendiri
            'kode_barang' => 'required|string|max:255|unique:inventaris,kode_barang,' . $item->id,
            'merk_model' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tahun_pembelian' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'jumlah' => 'required|integer|min:0',
            'harga_perolehan' => 'required|numeric|min:0',
            'kondisi' => 'required|in:B,KB,RB',
            'keterangan' => 'nullable|string',
        ]);
        
        // Jika validasi gagal, kembali ke form dengan error dan input lama
        if ($validator->fails()) {
            return redirect()->route('inventaris.edit', $item->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        // Jika validasi berhasil, update data
        $item->update($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('inventaris.index')
                       ->with('success', 'Data inventaris berhasil diperbarui.');
    }

    public function exportPDF(Request $request)
    {
        $query = Inventaris::query(); // Gunakan query() untuk memulai
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        $inventaris = $query->get();
        $selectedRoom = \App\Models\Room::find($request->room_id);

        $data = ['inventaris' => $inventaris, 'selectedRoom' => $selectedRoom];
        
        $pdf = PDF::loadView('inventaris.pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-inventaris-'.date('d-m-Y').'.pdf');
    }
}