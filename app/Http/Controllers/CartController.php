<?php

namespace App\Http\Controllers;

use App\DTO\Cart\AddProductToCartDTO;
use App\DTO\Cart\DeleteProductFromCartDTO;
use App\DTO\Cart\UpdateProductFromCartDTO;
use App\Http\Requests\Cart\AddProductToCartRequest;
use App\Http\Requests\Cart\DeleteProductFromCartRequest;
use App\Http\Requests\Cart\UpdateProductFromCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Service\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService=$cartService;
    }

    public function addProductToCart(AddProductToCartRequest $request)
    {
        $this->authorize("create",Cart::class);
        $result=$this->cartService->addProductToCart(AddProductToCartDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function getMyCartItems()
    {
        $this->authorize("view",Cart::class);
        $result=$this->cartService->getMyCartItems();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function updateProductFromCart(UpdateProductFromCartRequest $request)
    {
        $this->authorize("update",CartItem::find($request->input("cart_item_id")));
        $result=$this->cartService->updateProductFromCart(UpdateProductFromCartDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function deleteProductFromCart(DeleteProductFromCartRequest $request)
    {
        $this->authorize("delete",CartItem::find($request->input("cart_item_id")));
        $result=$this->cartService->deleteProductFromCart(DeleteProductFromCartDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }
}