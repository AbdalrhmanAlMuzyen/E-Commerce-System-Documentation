<?php
namespace App\DTO\ForgetPassword;

class ForgetPasswordDTO{
    public string $email;

    public function __construct(string $email)
    {
        $this->email=$email;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("email"));
    }
}