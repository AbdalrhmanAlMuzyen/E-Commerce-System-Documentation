<?php
namespace App\DTO\ForgetPassword;

class ResetPasswordDTO{
    public string $email;
    public string $code;
    public string $newPassword;

    public function __construct(string $email,string $code,string $newPassword)
    {
        $this->email=$email;
        $this->code=$code;
        $this->newPassword=$newPassword;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("email"),$request->input("code"),$request->input("newPassword"));
    }
}