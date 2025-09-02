<?php

namespace App\Http\Controllers;

// ===== PERBAIKAN: Gunakan Trait untuk middleware =====
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// ===== PERBAIKAN: Gunakan Model dan Class yang Benar =====
use App\Models\User;
use Spatie\Permission\Models\Role; // <-- Gunakan Role dari Spatie
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

// NOTE: Karena Anda mungkin akan menambahkan fitur PDF, 'use PDF;' dipertahankan
use PDF;

class UserController extends Controller
{
    use AuthorizesRequests; // <-- Tambahkan Trait ini

    /**
     * Terapkan middleware hak akses. Hanya SUPERADMIN yang boleh masuk.
     */
    public function __construct()
    {
        $this->middleware('role:SUPERADMIN');
    }

    /**
     * Menampilkan daftar semua pengguna dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);

        // Ambil semua user KECUALI Superadmin itu sendiri
        $usersQuery = User::whereHas('roles', fn($q) => $q->where('name', '!=', 'SUPERADMIN'))
                          ->with('roles'); // Eager load roles untuk efisiensi

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $usersQuery->orderBy('name', 'asc')->paginate($limit);

        return view('pages.user.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        // Ambil semua role KECUALI Superadmin untuk ditampilkan di dropdown
        $roles = Role::where('name', '!=', 'SUPERADMIN')->pluck('name', 'id');
        // Ambil semua user untuk pilihan 'parent' (atasan)
        $parents = User::orderBy('name')->pluck('name', 'id');

        return view('pages.user.create', compact('roles', 'parents'));
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi yang disesuaikan dengan struktur baru
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,id'], // Validasi role_id dari form
            'parent_id' => ['nullable', 'exists:users,id'],
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'parent_id' => $request->parent_id,
        ]);

        // ===== PERBAIKAN: Gunakan assignRole() dari Spatie =====
        $user->assignRole($request->role);

        return redirect()->route('user.index')->with('success', 'Pengguna baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit data pengguna.
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'SUPERADMIN')->pluck('name', 'id');
        // Pastikan user tidak bisa menjadi atasan untuk dirinya sendiri
        $parents = User::where('id', '!=', $user->id)->orderBy('name')->pluck('name', 'id');

        return view('pages.user.edit', compact('user', 'roles', 'parents'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'exists:roles,id'],
            'parent_id' => ['nullable', 'exists:users,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password opsional
        ]);

        // Update data dasar user
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'parent_id' => $request->parent_id,
        ]);

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // ===== PERBAIKAN: Gunakan syncRoles() dari Spatie untuk update =====
        $user->syncRoles([$request->role]);

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus data pengguna dari database.
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
        // Ambil semua user KECUALI Superadmin
        $users = User::whereHas('roles', fn($q) => $q->where('name', '!=', 'SUPERADMIN'))->get();
        $total_users = $users->count();

        $pdf = PDF::loadView('pages.user.cetak', compact('users', 'total_users'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-pengguna.pdf');
    }
}

