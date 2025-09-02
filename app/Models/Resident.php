<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    // Pastikan fillable Anda sudah benar
    protected $fillable = [
        'user_id',
        'status_tinggal',
        'jumlah',
    ];

    // ===================================================================
    // VVV INI ADALAH BAGIAN PERBAIKANNYA VVV
    /**
     * Mendefinisikan relasi balik "belongsTo" ke model User.
     * Ini memberitahu bahwa setiap data Resident dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // ===================================================================
}