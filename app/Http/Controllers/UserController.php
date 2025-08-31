<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role; // PENAMBAHAN: Tambahkan model Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // PENAMBAHAN: Untuk hashing password
use Illuminate\Validation\Rule; // PENAMBAHAN: Untuk validasi email unik saat update

// NOTE: Karena Anda menggunakan 'PDF', pastikan package-nya sudah ter-install
// dengan benar, contohnya barryvdh/laravel-dompdf
use PDF;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        // Ambil 'limit' dari URL, jika tidak ada, defaultnya adalah 10
        $limit = $request->query('limit', 10); 

        $usersQuery = User::with('role');

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // GANTI .get() DENGAN .paginate($limit)
        $users = $usersQuery->orderBy('name', 'asc')->paginate($limit);

        return view('pages.user.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        // PENAMBAHAN: Mengambil data roles untuk ditampilkan di form
        $roles = Role::all();
        return view('pages.user.create', compact('roles'));
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(Request $request)
    {
        // REVISI: Sesuaikan validasi dengan form create.blade.php
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'status' => ['required', 'in:aktif,tidak aktif'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // REVISI (SANGAT PENTING): Hash password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        // REVISI: Gunakan helper route() dan ganti pesan sukses
        return redirect()->route('user.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pengguna.
     * REVISI: Menggunakan Route Model Binding untuk kode yang lebih bersih.
     */
    public function edit(User $user) // <-- Perhatikan perubahan di sini
    {
        // PENAMBAHAN: Kirim juga data roles ke view edit
        $roles = Role::all();
        return view('pages.user.edit', compact('user', 'roles'));
    }

    /**
     * Memperbarui data pengguna di database.
     * REVISI: Menggunakan Route Model Binding.
     */
    public function update(Request $request, User $user)
    {
        // REVISI: Sesuaikan validasi dengan form edit.blade.php
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'status' => ['required', 'in:aktif,tidak aktif'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'min:8', 'confirmed'], // Password opsional
        ]);

        // REVISI: Logika untuk update password jika diisi
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Jika password tidak diisi, hapus dari array agar tidak menimpa password lama
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus data pengguna dari database.
     * REVISI: Menggunakan Route Model Binding.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Membuat dan menampilkan laporan pengguna dalam format PDF.
     */
    public function printPDF()
    {
        $users = User::all();

        // REVISI: Logika 'sum('jumlah')' tidak relevan untuk user, jadi dihapus.
        // Anda bisa menambahkan data lain jika perlu, misal total pengguna.
        $total_users = $users->count();

        $pdf = PDF::loadView('pages.user.cetak', [
            'users' => $users,
            'total_users' => $total_users
        ]);

        $pdf->setPaper('A4', 'portrait');

        // REVISI: Nama file laporan disesuaikan
        return $pdf->stream('laporan-data-pengguna.pdf');
    }
    
}