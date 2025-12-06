<?php

namespace App\Models;

// Import library yang dibutuhkan
use Illuminate\Database\Eloquent\Concerns\HasUuids; // PENTING: Agar ID otomatis jadi UUID
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // HasUuids: Mengaktifkan generate UUID otomatis saat create user
    // Notifiable: Agar user bisa menerima notifikasi email/database
    use HasFactory, Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     * Daftar kolom yang aman diisi via formulir (User::create($request->all()))
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',    // Nomor HP
        'address',  // Alamat lengkap
        'role',     // Peran: 'admin' atau 'customer'
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Kolom ini tidak akan muncul jika kita return data user ke JSON/API
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * Mengubah tipe data mentah database menjadi tipe data PHP yang sesuai
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis hash password saat disimpan
        ];
    }

    // --- RELASI ---

    /**
     * Satu User bisa memiliki BANYAK riwayat Rental (One to Many)
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}