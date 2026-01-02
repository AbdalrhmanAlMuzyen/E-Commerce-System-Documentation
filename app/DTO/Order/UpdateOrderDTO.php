<?php
namespace App\DTO\Order;

class UpdateOrderDTO{
    public int  $order_id;
    public string $status;

    public function __construct(int $order_id,string $status)
    {
        $this->order_id=$order_id;
        $this->status=$status;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("order_id"),$request->input("status"));
    }
}