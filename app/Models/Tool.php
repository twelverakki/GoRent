<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import fitur 'Tong Sampah'

class Tool extends Model
{
    // SoftDeletes: Saat dihapus, data tidak hilang permanen, cuma disembunyikan (deleted_at terisi)
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'category_id', // Foreign Key
        'name',
        'slug',
        'description',
        'image',
        'price_per_day',
        'stock',
        'is_available', // Status apakah barang bisa disewa
    ];

    // --- RELASI ---

    /**
     * Satu Alat pasti milik SATU Kategori (Inverse One to Many)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}