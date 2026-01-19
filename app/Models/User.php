<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $table="users";
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "password"
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function userLocation()
    {
        return $this->hasOne(UserLocation::class);
    }

    public function paymentRecords()
    {
        return $this->hasMany(PaymentRecord::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function resetPassword()
    {
        return $this->hasOne(ResetPassword::class);
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
