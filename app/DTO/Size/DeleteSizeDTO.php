<?php
namespace App\DTO\Size;

class DeleteSizeDTO{
    public int $size_id;

    public function __construct(int $size_id)
    {
        $this->size_id=$size_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("size_id"));
    }
}