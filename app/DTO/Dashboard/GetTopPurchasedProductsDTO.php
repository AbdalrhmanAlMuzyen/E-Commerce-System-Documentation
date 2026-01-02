<?php
namespace App\DTO\Dashboard;

class GetTopPurchasedProductsDTO{
    public int $user_id;

    public function __construct(int $user_id)
    {
        $this->user_id=$user_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("user_id"));
    }
}