<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    public const STATUS_LABELS = [
        'pending' => 'Menunggu',
        'paid' => 'Siap Ambil',
        'active' => 'Sedang Disewa',
        'completed' => 'Selesai',
        'overdue' => 'Terlambat',
        'cancelled' => 'Batal'
    ];

    protected $fillable = [
        'user_id',      // Siapa yang menyewa
        'invoice_no',   // Nomor unik nota
        'start_date',   // Mulai sewa
        'end_date',     // Rencana kembali
        'return_date',  // Tanggal asli kembali (diisi saat barang pulang)
        'total_price',
        'status',       // pending, paid, active, completed, overdue, cancelled
    ];

    /**
     * Mengubah kolom tanggal menjadi objek Carbon secara otomatis.
     * Keuntungan: Bisa langsung pakai format tanggal ($rental->start_date->format('d M Y'))
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    // --- RELASI ---

    /**
     * Satu Rental dimiliki oleh SATU User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Satu Rental bisa memuat BANYAK detail barang (One to Many)
     * Contoh: 1 Nota sewa isinya (1 Kamera + 2 Lensa)
     */
    public function items()
    {
        return $this->hasMany(RentalItem::class);
    }

    /**
     * Satu Rental mungkin punya SATU tagihan Denda (One to One)
     */
    public function fine()
    {
        return $this->hasOne(Fine::class);
    }
}