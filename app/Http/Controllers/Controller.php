<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- PASTIKAN INI ADA
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // VVV PASTIKAN BARIS INI ADA VVV
    // Inilah yang memberikan "kotak peralatan" ke semua controller Anda.
    use AuthorizesRequests, ValidatesRequests;
    // =============================================================
}

