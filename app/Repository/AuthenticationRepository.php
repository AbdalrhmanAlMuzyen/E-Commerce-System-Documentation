<?php

namespace App\Repository;

use App\DTO\Authentication\RegisterDTO;
use App\Models\RefreshToken;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
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

    public function createOrUpdateRefreshToken($user,$refreshToken,$device_id)
    {
        return $user->refreshTokens()->updateOrCreate(
        [
            "user_id"=>$user->id,
            "device_id"=>request()->input("device_id")
        ]
        ,
        [
            "refresh_token"=>$refreshToken,
            "device_id"=>$device_id,
            "expires_at"=>Carbon::now()->addDays(30)
        ]);
    }

    public function createOrUpdateResetPassword($email,$code)
    {
        return ResetPassword::updateOrCreate(
        [
            "email"=>$email
        ],
        [
            "token"=>$code,
            "expires_at"=>Carbon::now()->addMinutes(30)
        ]);
    }

    public function findUserByRefreshToken($refreshToken)
    {
        return User::whereHas("refreshTokens",function($q) use ($refreshToken){
            $q->where("refreshToken",$refreshToken);
        })->first();
    }

    public function findRefreshToken($refreshToken)
    {
        return RefreshToken::where("refresh_token",$refreshToken)->first();
    }

    public function findReset($user)
    {
        return $user->resetPassword()->first();
    }

    public function updateUserPassword($user,$newPassword)
    {
        return $user->update([
            "password"=>Hash::make($newPassword)
        ]);
    }

    public function deleteResetPassword($user)
    {
        return $user->resetPassword()->delete();
    }

    public function deleteRefreshToken($refreshToken)
    {
        return $refreshToken->delete();
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