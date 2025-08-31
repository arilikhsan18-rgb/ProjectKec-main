<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Daftar peran yang diizinkan mengakses route ini.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Asumsikan pengguna sudah login karena middleware 'auth' berjalan lebih dulu.
        // Gunakan relasi 'role' yang efisien, bukan query manual.
        // Tambahkan pengecekan `!Auth::user()->role` untuk menghindari error jika user tidak punya role.
        if (!Auth::user()->role || !in_array(Auth::user()->role->name, $roles)) {
            // Jika peran tidak cocok, berikan respon 403 Forbidden.
            // Ini adalah standar HTTP untuk masalah hak akses.
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK HALAMAN INI.');
        }

        // Jika peran cocok, lanjutkan ke request berikutnya.
        return $next($request);
    }
}
