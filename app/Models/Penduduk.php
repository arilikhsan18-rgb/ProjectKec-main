<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $fillable = 
    [ 'user_id','year','gender','resident','religion','education','occupation','lampid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
