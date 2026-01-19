<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'name',
        'delivery_fee',
    ];

    public function userLocations()
    {
        return $this->hasMany(UserLocation::class);
    }

    public function orders()
    {
        return $this->hasMany(Location::class);
    }
}
