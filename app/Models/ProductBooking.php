<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_phone',
        'pickup_time',
        'status',
        'notes',
        'notes_internal',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
