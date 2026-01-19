<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthenticationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user=JWTAuth::parseToken()->authenticate();
            if(!$user)
            {
                return response(["success"=>false,"message"=>"User not found","data"=>null],403);         
            }
        }
        catch(TokenExpiredException)
        {
            return response(["success"=>false,"message"=>"Your token has expired. Please login again.","data"=>null],403);         
        }
        catch(TokenBlacklistedException )
        {
            return response(["success"=>false,"message"=>"This token is no longer valid. Please login again.","data"=>null],403);         
        }
        catch(TokenInvalidException)
        {
            return response(["success"=>false,"message"=>"Invalid authentication token.","data"=>null],403);         
        }
        catch(JWTException)
        {
            return response(["success"=>false,"message"=>"Invalid authentication token.","data"=>null],403);         
        }
        return $next($request);
    }
}
