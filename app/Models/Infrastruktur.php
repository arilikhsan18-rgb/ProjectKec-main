<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infrastruktur extends Model
{
    protected $table= 'infrastrukturs';

    protected $guard= [];

    protected $fillable= [
        'gambar',
        'alamat',
        'ukuran',
        'keterangan',
    ];
}
