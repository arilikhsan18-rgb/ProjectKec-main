<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable = [
    'gender',
    'jumlah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
