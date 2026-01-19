<?php

namespace App\Http\Controllers;

use App\DTO\Authentication\LoginDTO;
use App\DTO\Authentication\LogoutDTO;
use App\DTO\Authentication\RegisterDTO;
use App\DTO\ForgetPassword\ForgetPasswordDTO;
use App\DTO\ForgetPassword\ResetPasswordDTO;
use App\DTO\RefreshToken\RefreshTokenDTO;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\LogoutRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Requests\ForgetPassword\ForegetPasswordRequest;
use App\Http\Requests\ForgetPassword\ResetPasswordRequest;
use App\Http\Requests\RefreshToken\RefreshTokenRequest;
use App\Service\AuthenticationService;
use Illuminate\Support\Facades\Cookie;

class AuthenticationController extends Controller
{
    protected $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService=$authenticationService;
    }

    public function register(RegisterRequest $request)
    {
        $result=$this->authenticationService->register(RegisterDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function login(LoginRequest $request)
    {  
        $result=$this->authenticationService->login(LoginDTO::FormRequest($request));
        if($result["success"])
        {
            return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"] )->withCookie("jwt",$result["data"]["token"],$result["data"]["token_duration"],'/',null,true,true,'Strict');
        }
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"] );
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $result=$this->authenticationService->refreshToken(RefreshTokenDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function logout(LogoutRequest $request)
    {
        $result=$this->authenticationService->logout(LogoutDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"])->withCookie(Cookie::forget('jwt'));
    }

    public function forgetPassword(ForegetPasswordRequest $request)
    {
        $result=$this->authenticationService->forgetPassword(ForgetPasswordDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"])->withCookie(Cookie::forget('jwt'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result=$this->authenticationService->resetPassword(ResetPasswordDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"])->withCookie(Cookie::forget('jwt'));
    }
}
