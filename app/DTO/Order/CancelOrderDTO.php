<?php
namespace App\DTO\Order;

class CancelOrderDTO{
    public int $order_id;

    public function __construct(int $order_id)
    {
        $this->order_id=$order_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("order_id"));
    }
}