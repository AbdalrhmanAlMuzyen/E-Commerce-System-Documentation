<?php
namespace App\Repository;

use App\DTO\Location\CreateLocationDTO;
use App\Models\Location;

class LocationRepository{
    
    public function createLocation(CreateLocationDTO $dto)
    {
        return Location::create([
            "name"=>$dto->name,
            "delivery_fee"=>$dto->delivery_fee
        ]);
    }

    public function findLocationByID($location_id)
    {
        return Location::find($location_id);
    }

    public function deleteLocation($location)
    {
        return $location->delete();
    }

    public function getLocations()
    {
        return Location::all();
    }

    public function updateLocation($location,array $data)
    {
        return $location->update($data);
    }

    public function getUserLocation($user)
    {
        return $user->userLocation;
    }
}