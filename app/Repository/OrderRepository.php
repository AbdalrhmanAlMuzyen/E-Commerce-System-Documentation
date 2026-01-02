<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\PaymentRecord;
use Stripe\PaymentIntent;

class OrderRepository{
    public function createOrder($user,$totalPrice,$location_id)
    {
        return $user->orders()->create([
            "total_price"=>$totalPrice,
            "user_location_id"=>$location_id
        ]);
    }

    public function createOrderItems($order,$cartItem)
    {
        return $order->orderItems()->create([
            "product_variant_id"=>$cartItem->product_variant_id,
            "quantity"=>$cartItem->quantity
        ]);
    }

    public function createPaymentIntent($order,$totalPrice)
    {
        return PaymentIntent::create([
            "amount"=>(int) round($totalPrice * 100),
            "currency"=>'usd',
            "metadata"=>[
                "user_id"=>$order->user_id,
                "order_id"=>$order->id
            ]
        ]);
    }

    public function createPaymentRecord($order,$paymentIntent_id,$totalPrice)
    {
        PaymentRecord::create([
            "order_id" => $order->id,
            "user_id" => $order->user_id,
            "stripe_payment_intent_id" => $paymentIntent_id,
            "amount" => $totalPrice,
        ]);
    }

    public function updateOrder($order,$status)
    {
        return $order->update([
            "status"=>$status
        ]);
    }
    
    public function getPaymentRecord($order)
    {
        return $order->paymentRecord;
    }

    public function updatePaymentRecord($order,$status,$failure_message)
    {
        return PaymentRecord::where("order_id",$order->id)->update([
            "status"=>$status,
            "failure_message"=>$failure_message
        ]);
    }

    public function getMyOrders($user)
    {
        return $user->orders()->with("orderItems")->orderBy("created_at")->get();
    }

    public function getOrders()
    {
        return Order::with("user","orderItems.productVariant.size","orderItems.productVariant.color")->orderBy("created_at")->get();
    }

    public function findOrderByID($user,$order_id)
    {
        return $user->orders()->find($order_id);
    }

    public function findOrderByJustID($order_id)
    {
        return Order::find($order_id);
    }
}