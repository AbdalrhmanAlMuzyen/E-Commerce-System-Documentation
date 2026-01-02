<?php

namespace App\DTO\Location;

class UpdateLocationDTO{
    public int $location_id;
    public ?string $name;
    public ?float $delivery_fee;

    public function __construct(int $location_id,?string $name,?float $delivery_fee)
    {
        $this->location_id=$location_id;
        $this->name=$name;
        $this->delivery_fee=$delivery_fee;
    }

    public  static function FormRequest($request)
    {
        return new self($request->input("location_id"),$request->input("name"),$request->input("delivery_fee"));
    }
}