<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Pastikan ini ada

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Pastikan HasRoles ada di sini

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username', // Pastikan username ada di sini
        'email',
        'password',
        'parent_id', // Pastikan parent_id ada di sini
        'nama_kelurahan',
        'nomor_rw',
        'nomor_rt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===================================================================
    // VVV INI ADALAH BAGIAN PERBAIKANNYA VVV
    // Mendefinisikan semua "grup kontak" atau hubungan yang dimiliki User
    // ===================================================================

    /**
     * Mendefinisikan relasi one-to-many ke data Resident.
     * Satu user (RT) bisa menginput banyak data resident.
     */
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Relasi ke data Year.
     */
    public function years()
    {
        return $this->hasMany(Year::class);
    }

    /**
     * Relasi ke data Education.
     */
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Relasi ke data Occupation.
     */
    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }

     public function lampids()
    {
        // Pastikan Anda sudah punya Model Lampid
        return $this->hasMany(Lampid::class);
    }

    public function fasums()
    {
        return $this->hasmany(Fasum::class);
    }

    public function geografiss()
    {
        return $this->hasmany(Geografis::class);
    }

    public function Genders()
    {
        return $this->hasmany(Gender::class);
    }
}

