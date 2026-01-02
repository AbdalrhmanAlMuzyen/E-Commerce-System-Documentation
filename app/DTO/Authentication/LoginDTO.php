<?php
namespace App\DTO\Authentication;

class LoginDTO{
    public string $email;
    public string $passowrd;

    public function __construct(string $email,string $passowrd)
    {
        $this->email=$email;
        $this->passowrd=$passowrd;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("email"),$request->input("password"));
    }
}