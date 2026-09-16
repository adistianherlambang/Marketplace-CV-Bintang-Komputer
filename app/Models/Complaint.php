<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'contact',
        'complaint_type',
        'description',
        'complaint_text',
        'nota_bukti',
        'product_bukti',
        'status',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
