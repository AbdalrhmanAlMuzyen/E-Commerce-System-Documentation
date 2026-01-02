<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    protected $table = 'payment_records';

    protected $fillable = [
        'order_id',
        'user_id',
        'stripe_payment_intent_id',
        'reference',
        'amount',
        'currency',
        'status',
        'gateway',
        'failure_message',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }    
}
