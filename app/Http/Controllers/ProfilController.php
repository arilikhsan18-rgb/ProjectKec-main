<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        // Di sini Anda bisa mengambil data dari database di masa depan.
        // Untuk saat ini, kita hanya langsung menampilkan view-nya.
        
        return view('profil');
    }
}
