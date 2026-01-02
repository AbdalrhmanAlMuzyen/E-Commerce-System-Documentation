<?php

namespace App\Http\Controllers;

use App\DTO\Authentication\LoginDTO;
use App\DTO\Authentication\RegisterDTO;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Service\AuthenticationService;

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
        if($result["success"] && $result["data"]["is_admin"])
        {
            return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"] )->withCookie("JWT",$result["data"]["token"],60*24,'/',null,true,true,'Strict');
        }
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function logout()
    {
        $result=$this->authenticationService->logout();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }
}
