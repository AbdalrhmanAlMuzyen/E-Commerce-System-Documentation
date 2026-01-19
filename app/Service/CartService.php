<?php
namespace App\Service;

use App\DTO\Cart\AddProductToCartDTO;
use App\DTO\Cart\DeleteProductFromCartDTO;
use App\DTO\Cart\UpdateProductFromCartDTO;
use App\Repository\CartRepository;
use App\Repository\ProductVariantRepository;
use App\ReturnTrait;
use Illuminate\Support\Facades\Auth;

class CartService{
    use ReturnTrait;
    protected $cartRepository;
    protected $productVariantRepository;
    public function __construct(CartRepository $cartRepository,ProductVariantRepository $productVariantRepository)
    {
        $this->cartRepository=$cartRepository;
        $this->productVariantRepository=$productVariantRepository;
    }

    public function addProductToCart(AddProductToCartDTO $dto)
    {
        try{
            $user=Auth::guard("user")->user();
            $productVariant=$this->productVariantRepository->findProductVariantByID($dto->product_variant_id);
            if(!$productVariant)
            {
                return $this->error(false,"product variant not found",null,404);
            }
            $cart=$this->cartRepository->getCart($user);
            if(!$cart)
            {
                $cart=$this->cartRepository->createCart($user);
            }

            if($cartItem=$this->cartRepository->getCartItemByVariant($cart,$productVariant->id))
            {
                $this->cartRepository->updateProductFromCart($cartItem,$dto->quantity);
                return $this->success(true,"product update from your cart successfully",$cartItem,201);
            }
            
            $cartItem=$this->cartRepository->addProductToCart($cart,$user,$dto);

            return $this->success(true,"product added to your cart successfully",$cartItem,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function deleteProductFromCart(DeleteProductFromCartDTO $dto)
    {
        try{
            $user=Auth::guard("user")->user();
            $cart=$this->cartRepository->getCart($user);
            $cartItem=$this->cartRepository->findCartItemByID($cart,$dto->cart_item_id);
            if(!$cartItem)
            {
                return $this->error(false,"cart item not found",null,404);
            }
            $this->cartRepository->deleteProductFromCart($cartItem);
            return $this->success(true,"product deleted from your cart successfully",null,200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function updateProductFromCart(UpdateProductFromCartDTO $dto)
    {
        try{
            $user=Auth::guard("user")->user();
            $cart=$this->cartRepository->getCart($user);
            $cartItem=$this->cartRepository->findCartItemByID($cart,$dto->cart_item_id);
            if(!$cartItem)
            {
                return $this->error(false,"cart item not found",null,404);
            }
            $this->cartRepository->updateProductFromCart($cartItem,$dto->quantity);
            return $this->success(true,"product updated from your cart successfully",$cartItem->fresh(),200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function getMyCartItems()
    {
        try{
            $user=Auth::guard("user")->user();
            $cart=$this->cartRepository->getCart($user);
            $cartItems=$this->cartRepository->getMyCartItems($cart); 
            if($cartItems->isEmpty())
            {
                return $this->error(false,"your cart is empty",[],404);
            }
            return $this->success(true,"Cart retrieved successfully",$cartItems,200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }
}