<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampid extends Model
{
    protected $fillable = [
        'status',
        'jumlah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
