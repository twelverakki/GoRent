<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'rental_id',    // Milik nota mana
        'tool_id',      // Barang apa yang disewa
        'quantity',     // Jumlah barang
        'price_snapshot', // PENTING: Harga barang SAAT transaksi terjadi (bukan harga sekarang)
        'subtotal',     // (quantity * price_snapshot)
    ];

    // --- RELASI ---

    /**
     * Item ini bagian dari SATU Rental
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Item ini merujuk ke SATU Tool
     */
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}