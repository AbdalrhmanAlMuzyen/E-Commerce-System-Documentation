<?php

namespace App\DTO\Product;

class CreateProductDTO{
    public int $category_id;
    public string $name;
    public string $description;
    public float $price;

    public function __construct(int $category_id,string $name,string $description,float $price)
    {
        $this->category_id=$category_id;
        $this->name=$name;
        $this->description=$description;
        $this->price=$price;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("category_id"),$request->input("name"),$request->input("description"),$request->input("price"));
    }
}