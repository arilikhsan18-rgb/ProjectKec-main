<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Opsional, tapi baik untuk ada
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory; // Opsional, tapi baik untuk ada

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    // Anda tidak perlu baris ini jika nama tabel Anda sudah 'roles'
    // karena Laravel otomatis mendeteksinya dari nama model 'Role'.
    // Tapi tidak apa-apa jika tetap ada.
    // protected $table = 'roles';
}