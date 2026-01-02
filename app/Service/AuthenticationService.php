<?php

namespace App\Service;

use App\DTO\Authentication\LoginDTO;
use App\DTO\Authentication\RegisterDTO;
use App\Repository\AuthenticationRepository;
use App\ReturnTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationService{
    use ReturnTrait;
    protected $authenticationRepository;

    public function __construct(AuthenticationRepository $authenticationRepository)
    {
        $this->authenticationRepository=$authenticationRepository;
    }

    public function register(RegisterDTO $dto)
    {
        try{
            DB::beginTransaction();
                $user=$this->authenticationRepository->register($dto);
                $this->authenticationRepository->createUserLocation($user,$dto);
                $user->assignRole("user");
            DB::commit();    
            
            return $this->success(true, "account created successfully",$user,201);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $this->error(false,$e->getMessage());
        }
    }

    public function login(LoginDTO $dto)
    {
        try{
            $user=$this->authenticationRepository->findUserByEmail($dto->email);
            if($user && Hash::check($dto->passowrd,$user->password))
            {
               return $this->success(true,"login successfully",["user"=>$user,"is_admin"=>$user->hasRole("admin"),"token"=>JWTAuth::fromUser($user)]);
            }
            return $this->error(false,"wrong email or password",null,401);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }
                            
    public function logout()
    {
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->success(true,"logout successfully");
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }
}