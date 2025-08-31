<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $table= 'years';

    protected $guard= [];

    protected $fillable = [
        'tahun_lahir', // <-- TAMBAHKAN BARIS INI
        'jumlah',];
}
