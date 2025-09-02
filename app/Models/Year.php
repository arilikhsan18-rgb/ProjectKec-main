<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tahun_lahir', 'jumlah'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}