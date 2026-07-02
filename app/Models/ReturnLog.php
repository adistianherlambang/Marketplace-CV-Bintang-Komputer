<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnLog extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'reason',
        'status',
        'date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
