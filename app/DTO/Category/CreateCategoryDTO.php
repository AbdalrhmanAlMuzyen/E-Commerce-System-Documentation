<?php
namespace App\DTO\Category;

class CreateCategoryDTO{
    public string $name;
    public string $image;

    public function __construct(string $name,string $image)
    {
        $this->name=$name;
        $this->image=$image;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("name"),$request->file("image"));
    }
}