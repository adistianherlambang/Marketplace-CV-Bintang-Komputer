<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    // Tambahkan baris fillable ini agar kolom-kolomnya diizinkan untuk diisi
    protected $fillable = [
        'user_id',
        'total_harga',
        'status_pembayaran',
        'status_pengiriman',
        'nomor_resi',
        'alamat_pengiriman'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}