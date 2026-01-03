<?php

namespace App\Service;

use App\DTO\Order\CancelOrderDTO;
use App\DTO\Order\UpdateOrderDTO;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Repository\CartRepository;
use App\Repository\LocationRepository;
use App\Repository\OrderRepository;
use App\ReturnTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class OrderService{
    use ReturnTrait;
    protected $orderRepository;
    protected $cartRepository;
    protected $locationRepository;
    public function __construct(OrderRepository $orderRepository,CartRepository $cartRepository,LocationRepository $locationRepository)
    {
        $this->orderRepository=$orderRepository;
        $this->cartRepository=$cartRepository;
        $this->locationRepository=$locationRepository;
    }

    //checkout
    public function checkOut()
    {
        try {
            $user = Auth::guard("user")->user();
            $cart = $this->cartRepository->getCart($user);
            $cartItems = $this->cartRepository->getCartItems($cart);
            $location=$this->locationRepository->getUserLocation($user);
            if ($cartItems->isEmpty()) {
                return $this->error(false, "your cart is empty", null, 404);
            }

            DB::beginTransaction();

                $totalPrice = $location->delivery_fee;

                foreach ($cartItems as $cartItem) {
                    $productVariant = ProductVariant::lockForUpdate()->find($cartItem->product_variant_id);

                    if (($productVariant->total_stock - $productVariant->total_reserved_stock) < $cartItem->quantity) {
                        throw new Exception("Not enough stock");
                    }

                    $productVariant->increment("total_reserved_stock", $cartItem->quantity);

                    $totalPrice += $productVariant->product->price * $cartItem->quantity;
                }

                $order = $this->orderRepository->createOrder($user, $totalPrice,$location->id);

                foreach ($cartItems as $cartItem) {
                    $this->orderRepository->createOrderItems($order, $cartItem);
                }

                $paymentIntent = $this->orderRepository->createPaymentIntent($order, $totalPrice);
                $this->orderRepository->createPaymentRecord($order, $paymentIntent->id, $totalPrice);
                $this->cartRepository->deleteCartItems($cart);
            DB::commit();

            return $this->success(true, "done", [
                "client_secret" => $paymentIntent->client_secret
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(false, $e->getMessage());
        }
    }

    //webhook
    public function handleWebhook()
    {
        try {
            $payload = request()->getContent();
            $sigHeader = request()->header("Stripe-Signature");
            $webhookSecret = config("services.stripe.webhook_secret");

            $event = Webhook::constructEvent($payload,$sigHeader,$webhookSecret);

            match ($event->type) {
                'payment_intent.succeeded' =>$this->paymentSucceeded($event->data->object),
                'payment_intent.payment_failed' =>$this->paymentFailed($event->data->object),
                'payment_intent.canceled' =>$this->paymentCancelled($event->data->object),
                default => null
            };

            return $this->success(true,"done",null,200);
        } catch (\Exception $e) {
            return $this->error(false,$e->getMessage());
        }
    }

    //success
    public function paymentSucceeded($paymentIntent)
    {
        try{
            DB::beginTransaction();
                $order=Order::lockForUpdate()->find($paymentIntent->metadata->order_id);
                $this->releaseReservedStock($order->orderItems,true);
                $this->orderRepository->updateOrder($order,"paid");
                $this->orderRepository->updatePaymentRecord($order,"success",null);
            DB::commit();
        }
        catch(\Exception)
        {
            DB::rollBack();
            throw new Exception("payment failed");
        }
    }

    //failed
    public function paymentFailed($paymentIntent)
    {
        try{
            DB::beginTransaction();
                $order=Order::lockForUpdate()->find($paymentIntent->metadata->order_id);
                $this->releaseReservedStock($order->orderItems,false);               
                $this->orderRepository->updateOrder($order,"failed");
                $this->orderRepository->updatePaymentRecord($order,"failed",$paymentIntent->last_payment_error->message);
            DB::commit();
        }
        catch(\Exception)
        {
            DB::rollBack();
            throw new Exception("payment failed");
        }
    }

    //cancelled
    public function paymentCancelled($paymentIntent)
    {
        try{
            DB::beginTransaction();
                $order=Order::lockForUpdate()->find($paymentIntent->order_id);
                $this->releaseReservedStock($order->orderItems,false);
                $this->orderRepository->updateOrder($order,"cancelled");
                $this->orderRepository->updatePaymentRecord($order,"cancelled","Stripe cancelled");
            DB::commit();
        }
        catch(\Exception)
        {
            DB::rollBack();
            throw new Exception("payment failed");
        }
    }

    //get
    public function getMyOrders()
    {
        try{
            $user=Auth::guard("user")->user();
            $orders=$this->orderRepository->getMyOrders($user);
            if($orders->isEmpty())
            {
                return $this->error(false,"dont have orders yet",[],404);
            }

            return $this->success(true,"Orders retrieved successfully",$orders,200);
        }
        catch(\Exception $e)
        {
            $this->error(false,$e->getMessage());
        }
    }

    public function allowedStatusTransitions()
    {
        return [
            "paid"=>["shipped"],
            "shipped"=>["delivered"]
        ];
    }

    //update order
    public function updateOrder(UpdateOrderDTO $dto)
    {
        try{
            $order=$this->orderRepository->findOrderByJustID($dto->order_id);
            if (!$order) {
                return $this->error(false, "Order not found", null, 404);
            }

            if ($order->status === 'cancelled') {
                return $this->error(false, "Cancelled order cannot be updated", null, 422);
            }

            $transitions =$this->allowedStatusTransitions();
            if (!in_array($dto->status, $transitions[$order->status])) {
                return $this->error(false,"Invalid status transition from {$order->status} to {$dto->status}",null,422);
            }

            $this->orderRepository->updateOrder($order,$dto->status);
            return $this->success(true,"Order updated successfully",$order->fresh());
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    //cancel order
    public function cancelOrder(CancelOrderDTO $dto)
    {
        try{
            DB::beginTransaction();
                $user=Auth::guard("user")->user();
                $order=$this->orderRepository->findOrderByID($user,$dto->order_id);
                if(!$order || $order->status !="pending")
                {
                    return $this->error(false,"Order can not be cancelled",null,400);
                }

                $this->orderRepository->updateOrder($order,"cancelled");
                $this->releaseReservedStock($order->orderItems,false);
                $paymentRecord=$this->orderRepository->getPaymentRecord($order);
                $this->orderRepository->updatePaymentRecord($order,"cancelled","User cancelled");
                $paymentIntent=PaymentIntent::retrieve($paymentRecord->stripe_payment_intent_id);
                $paymentIntent->cancel();
            DB::commit();    
            return $this->success(true,"Order cancelled successfully",null,200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $this->error(false,$e->getMessage());
        }
    }
    
    public function getOrders()
    {
        try {
            $orders = $this->orderRepository->getOrders();

            if ($orders->isEmpty()) {
                return $this->success(true,"No orders found",[],200);
            }

            return $this->success(true,"Orders retrieved successfully",$orders,200);

        } catch (\Exception $e) {
            return $this->error(false,$e->getMessage());
        }
    }

    /*******/
    public function releaseReservedStock($orderItems,$status)
    {
        foreach($orderItems as $orderItem)
        {
            $productVariant=ProductVariant::find($orderItem->product_variant_id);
            $productVariant->decrement("total_reserved_stock",$orderItem->quantity);
            if($status)
            {
                $productVariant->decrement("total_stock",$orderItem->quantity);
            }
        }                
    }
}