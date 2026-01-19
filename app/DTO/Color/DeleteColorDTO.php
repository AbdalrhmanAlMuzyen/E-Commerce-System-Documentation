<?php
namespace App\DTO\Color;

class DeleteColorDTO{
    public int $color_id;

    public function __construct(int $color_id)
    {
        $this->color_id=$color_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("color_id"));
    }
}