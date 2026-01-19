<?php

namespace App\DTO\Authentication;

class LogoutDTO{
    public ?string $refreshToken;

    public function __construct(?string $refreshToken)
    {
        $this->refreshToken=$refreshToken;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("refreshToken"));
    }  
}