<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table= 'rooms';

    protected $guard= [];

    protected $fillable = [
        'name', // <-- TAMBAHKAN BARIS INI
        'code',];
}
