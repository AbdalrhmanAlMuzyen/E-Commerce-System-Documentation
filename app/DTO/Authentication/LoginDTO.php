<?php
namespace App\DTO\Authentication;

class LoginDTO{
    public string $email;
    public string $passowrd;
    public ?string $device_id;

    public function __construct(string $email,string $passowrd,?string $device_id)
    {
        $this->email=$email;
        $this->passowrd=$passowrd;
        $this->device_id=$device_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("email"),$request->input("password"),$request->input("device_id"));
    }
}