<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids; // <--- 1. Import ini

class RentalItem extends Model
{
    use HasFactory, HasUlids; // <--- 2. Pasang di sini

    protected $fillable = [
        'rental_id',
        'tool_id',
        'quantity',
        'price_snapshot',
        'subtotal',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}