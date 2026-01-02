<?php

namespace App\Repository;

use App\DTO\Authentication\RegisterDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationRepository{
    
    public function register(RegisterDTO $dto)
    {
        return User::create([
            "first_name"=>$dto->first_name,
            "last_name"=>$dto->last_name,
            "email"=>$dto->email,
            "password"=>Hash::make($dto->passowrd)
        ]);
    }
    public function createUserLocation($user,RegisterDTO $dto)
    {
        return $user->userLocation()->create([
            "location_id"=>$dto->location_id,
            "building_number"=>$dto->building_number,
            "address_details"=>$dto->address_details
        ]);
    }

    public function findUserByEmail($email)
    {
        return User::where("email",$email)->first();
    }

    public function findUserByID($user_id)
    {
        return User::find($user_id);
    }
}