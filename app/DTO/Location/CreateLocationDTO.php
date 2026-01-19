<?php
namespace App\DTO\Location;

class CreateLocationDTO{
    public string $name;
    public float $delivery_fee;

    public function __construct(string $name,float $delivery_fee)
    {
        $this->name=$name;
        $this->delivery_fee=$delivery_fee;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("name"),$request->input("delivery_fee"));
    }
}