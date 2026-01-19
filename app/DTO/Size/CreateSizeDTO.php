<?php

namespace App\DTO\Size;

use PhpParser\Node\Scalar\Int_;

class CreateSizeDTO{
    public int $value;

    public function __construct(int $value)
    {
        $this->value=$value;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("value"));
    }
}