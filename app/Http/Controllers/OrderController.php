<?php

namespace App\Http\Controllers;

use App\DTO\Order\CancelOrderDTO;
use App\DTO\Order\UpdateOrderDTO;
use App\Http\Requests\order\CancelOrderRequest;
use App\Http\Requests\order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\ReturnTrait;
use App\Service\OrderService;

class OrderController extends Controller
{
    protected $orderService;
    use ReturnTrait;

    public function __construct(OrderService $orderService)
    {
        $this->orderService=$orderService;
    }

    public function checkOut()
    {
        $this->authorize("checkOut",User::class);
        $result=$this->orderService->checkOut();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getMyOrders()
    {
        $this->authorize("view",Order::class);
        $result=$this->orderService->getMyOrders();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);                
    }

    public function cancelOrder(CancelOrderRequest $request)
    {        
        $this->authorize("cancel",Order::class);
        $result=$this->orderService->cancelOrder(CancelOrderDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }

    public function getOrders()
    {
        $this->authorize("get",Order::class);
        $result=$this->orderService->getOrders();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }

    public function updateOrder(UpdateOrderRequest $request)
    {
        $this->authorize("update",Order::class);
        $result=$this->orderService->updateOrder(UpdateOrderDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }
}
