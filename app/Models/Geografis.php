<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geografis extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // <-- WAJIB ADA: untuk menghubungkan data dengan user
        'luas_wilayah',
        'batas_wilayah_utara',
        'batas_wilayah_selatan',
        'batas_wilayah_barat',
        'batas_wilayah_timur',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Ini memberitahu bahwa setiap data Geografis DIMILIKI OLEH SATU User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}