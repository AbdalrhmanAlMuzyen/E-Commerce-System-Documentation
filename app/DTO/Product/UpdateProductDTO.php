<?php

namespace App\DTO\Product;

class UpdateProductDTO{
    public int $product_id;
    public ?string $name;
    public ?string $description;
    public ?float $price;
  
    public function __construct(int $product_id,?string $name,?string $description,?float $price)
    {
        $this->product_id=$product_id;
        $this->name=$name;
        $this->description=$description;
        $this->price=$price;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("product_id"),$request->input("name"),$request->input("description"),$request->input("price"));
    }
}