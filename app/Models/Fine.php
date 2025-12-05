<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'rental_id', // Terhubung ke transaksi rental mana
        'amount',    // Nominal denda
        'reason',    // Alasan (Telat, Rusak, Hilang)
        'status',    // unpaid (belum lunas), paid (lunas)
    ];

    // --- RELASI ---

    /**
     * Denda ini milik SATU Rental spesifik
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}