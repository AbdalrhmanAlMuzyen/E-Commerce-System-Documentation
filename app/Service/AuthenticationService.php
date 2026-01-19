<?php

namespace App\Service;

use App\DTO\Authentication\LoginDTO;
use App\DTO\Authentication\LogoutDTO;
use App\DTO\Authentication\RegisterDTO;
use App\DTO\ForgetPassword\ForgetPasswordDTO;
use App\DTO\ForgetPassword\ResetPasswordDTO;
use App\DTO\RefreshToken\RefreshTokenDTO;
use App\Mail\ResetPassword;
use App\Repository\AuthenticationRepository;
use App\ReturnTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
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
                $refreshToken=Str::random(64);
                $this->authenticationRepository->createOrUpdateRefreshToken($user,$refreshToken,$dto->device_id);
                $this->authenticationRepository->createUserLocation($user,$dto);
                $user->assignRole("user");
            DB::commit();    

            return $this->success(true, "account created successfully",[
                    "user"=>$user,
                    "refreshToken"=>$refreshToken,
                    "token"=>JWTAuth::fromUser($user),
                    "token_duration"=>Auth::guard("user")->factory()->getTTL(),
                    "expires_at"=>Carbon::now()->addMinutes(Auth::guard("user")->factory()->getTTL()),
            ],201);
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
            //$agent=new Agent();
            
            if($user && Hash::check($dto->passowrd,$user->password))
            {
                if($user->hasRole("admin") /*&& $agent->isMobile()*/)
                {
                    $refreshToken=null;
                }

                elseif($user->hasRole("user") /*&& $agent->isDesctop()*/)
                {
                    $refreshToken=Str::random(64);
                    $this->authenticationRepository->createOrUpdateRefreshToken($user,$refreshToken,$dto->device_id);                    
                }
                else{
                    return $this->error(false,"Invalid device for this role",null);
                }

                return $this->success(true,"Login successfully",[
                    "user"=>$user,
                    "refreshToken"=>$refreshToken,
                    "token"=>JWTAuth::fromUser($user),
                    "token_duration"=>Auth::guard("user")->factory()->getTTL(),
                    "expires_at"=>now()->addMinutes(Auth::guard("user")->factory()->getTTL()),
                ]);
            
            }
            return $this->error(false,"wrong email or password",null,401);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function logout(LogoutDTO $dto)
    {
        try{
            $refreshToken=$this->authenticationRepository->findRefreshToken($dto->refreshToken);
            $this->authenticationRepository->deleteRefreshToken($refreshToken);
            return $this->success(true,"logout successfully");
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function forgetPassword(ForgetPasswordDTO $dto)
    {
        try{
            $user=$this->authenticationRepository->findUserByEmail($dto->email);
            if(!$user)
            {
                return $this->error(false,"User not found",null,404);
            }
            $code=Str::random(10);
            Mail::to($user->email)->send(new ResetPassword($code));
            $this->authenticationRepository->createOrUpdateResetPassword($user->id,$code);
            return $this->success(true,"Check your gmail",null,200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function resetPassword(ResetPasswordDTO $dto)
    {
        try {
            $user = $this->authenticationRepository->findUserByEmail($dto->email);

            if (!$user) {
                return $this->error(false, "User not found", null, 404);
            }

            $resetPassword = $this->authenticationRepository->findReset($user);

            if (!$resetPassword) {
                
                return $this->error(false, "Reset request not found", null, 404);
            }
            
            if ($resetPassword->code !== $dto->code) {
                return $this->error(false, "Invalid reset code", null, 401);
            }

            if (Carbon::parse($resetPassword->expires_at)->isPast()) {
                return $this->error(false, "Reset code has expired", null, 401);
            }

            $this->authenticationRepository->updateUserPassword($user,$dto->newPassword);

            $this->authenticationRepository->deleteResetPassword($user);

            return $this->success(true, "Password changed successfully", null);

        } catch (\Exception $e) {
            return $this->error(false,$e->getMessage());
        }
    }


    public function refreshToken(RefreshTokenDTO $dto)
    {
        try {
            $refreshToken = $this->authenticationRepository
                ->findRefreshToken($dto->refreshToken);

            if (!$refreshToken) {
                return $this->error(false, 'Invalid refresh token', null, 401);
            }

            if (!$this->isRefreshTokenValid($refreshToken)) {
                return $this->error(false, 'Refresh token expired', null, 401);
            }
            return $this->success(true,"done",[
                "token"=>JWTAuth::fromUser($refreshToken->user),
                "token_duration"=>Auth::guard("user")->factory()->getTTL(),
                "expires_at"=>Carbon::now()->addMinutes(Auth::guard("user")->factory()->getTTL()),
            ],200);

        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage(), null, 500);
        }
    }
 
    public function isRefreshTokenValid($refreshToken)
    {
        return Carbon::parse($refreshToken->expires_at)->greaterThan(Carbon::now());
    }

}