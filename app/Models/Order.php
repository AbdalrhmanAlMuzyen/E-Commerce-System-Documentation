<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'user_location_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentRecord()
    {
        return $this->hasOne(PaymentRecord::class);
    }

    public function userLocation()
    {
        return $this->belongsTo(UserLocation::class);
    }
}