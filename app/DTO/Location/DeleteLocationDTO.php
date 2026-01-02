<?php

namespace App\DTO\Location;

class DeleteLocationDTO{
    public int $location_id;
    
    public function __construct(int $location_id)
    {
        $this->location_id=$location_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("location_id"));
    }
}