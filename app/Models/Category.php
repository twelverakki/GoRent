<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Menggunakan UUID sebagai Primary Key
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug' // URL friendly version dari name (misal: 'kamera-sony')
    ];

    // --- RELASI ---

    /**
     * Satu Kategori menampung BANYAK Alat (One to Many)
     */
    public function tools()
    {
        return $this->hasMany(Tool::class);
    }
}