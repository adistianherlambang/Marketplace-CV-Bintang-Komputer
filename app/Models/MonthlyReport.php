<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_month',
        'total_sales',
        'total_earnings',
        'total_transactions',
        'generated_at',
    ];

    protected $casts = [
        'total_sales' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_transactions' => 'integer',
        'generated_at' => 'datetime',
    ];
}
