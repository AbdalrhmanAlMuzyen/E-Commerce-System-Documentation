<?php
namespace App\DTO\RefreshToken;

class RefreshTokenDTO{
    public string $refreshToken;

    public function __construct(string $refreshToken)
    {
        $this->refreshToken=$refreshToken;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("refreshToken"));
    }
}