<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasum extends Model
{
    protected $fillable = [
        'nama',
        'jumlah'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
