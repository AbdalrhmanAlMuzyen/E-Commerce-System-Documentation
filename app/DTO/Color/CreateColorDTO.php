<?php
namespace App\DTO\Color;

class CreateColorDTO{

    public string $name;

    public function __construct(string $name)
    {
        $this->name=$name;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("name"));
    }
}