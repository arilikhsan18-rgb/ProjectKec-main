<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Resident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'address',
        'phone_number',
        'birth_date',
        'user_id',
    ];

    /**
     * Get the user that owns the resident.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
