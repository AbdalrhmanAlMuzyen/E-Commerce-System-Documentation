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

ؤ
}