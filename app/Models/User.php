<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Kolom-kolom ini boleh diisi secara massal, misalnya saat seeder dijalankan.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username', // Tambahkan username
        'email',
        'password',
        'status', // Tambahkan status
        'role_id',
        'parent_id', // Tambahkan parent_id
        'nama_kelurahan', // Tambahkan info wilayah
        'nomor_rw',       // Tambahkan info wilayah
        'nomor_rt',       // Tambahkan info wilayah
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

    /**
     * Mendefinisikan relasi: Setiap User memiliki satu Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mendefinisikan relasi: Setiap User (misal: RT) memiliki satu induk (yaitu RW-nya).
     * Ini adalah relasi ke model User itu sendiri.
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Mendefinisikan relasi: Setiap User (misal: RW) memiliki banyak anak (yaitu semua RT di bawahnya).
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
}
