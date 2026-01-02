<?php

namespace App\Http\Controllers;

use App\DTO\ProductVariant\CreateProductVariantDTO;
use App\DTO\ProductVariant\DeleteProductVariantDTO;
use App\DTO\ProductVariant\GetProductVariantDTO;
use App\DTO\ProductVariant\UpdateProductVariantDTO;
use App\Http\Requests\ProductVariant\CreateProductVariantRequest;
use App\Http\Requests\ProductVariant\DeleteProductVariantRequest;
use App\Http\Requests\ProductVariant\GetProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Models\ProductVariant;
use App\ReturnTrait;
use App\Service\ProductVariantService;

class ProductVariantController extends Controller
{
    use ReturnTrait;
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService=$productVariantService;
    }

    public function createProductVariant(CreateProductVariantRequest $request)
    {
        $this->authorize("create",ProductVariant::class);
        $result=$this->productVariantService->createProductVariant(CreateProductVariantDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);            
    }

    public function getProudctVariants(GetProductVariantRequest $request)
    {
        $result=$this->productVariantService->getProudctVariants(GetProductVariantDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function deleteProductVariant(DeleteProductVariantRequest $request)
    {
        $result=$this->productVariantService->deleteProductVariant(DeleteProductVariantDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function updateProductVariant(UpdateProductVariantRequest $request)
    {
        $this->authorize("update",ProductVariant::class);
        $result=$this->productVariantService->updateProductVariant(UpdateProductVariantDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }
}