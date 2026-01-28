<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $casts = [
        'raw_response' => 'array',
        'paid_at' => 'datetime'
    ];

    protected $fillable = [
        'order_id',
        'payment_type',
        'amount',
        'transaction_status',
        'raw_response',
        'paid_at'
    ];
}
