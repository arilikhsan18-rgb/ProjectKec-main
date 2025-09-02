<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pekerjaan', 'jumlah'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
