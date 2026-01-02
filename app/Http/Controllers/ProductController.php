<?php

namespace App\Http\Controllers;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\DeleteProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\DeleteProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Service\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService=$productService;
    }

    public function createProduct(CreateProductRequest $request)
    {
        $this->authorize("create",Product::class);
        $result=$this->productService->createProduct(CreateProductDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function deleteProduct(DeleteProductRequest $request)
    {
        $this->authorize("delete",Product::class);
        $result=$this->productService->deleteProduct(DeleteProductDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }
    
    public function updateProduct(UpdateProductRequest $request)
    {
        $this->authorize("update",Product::class);
        $result=$this->productService->updateProduct(UpdateProductDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }    
}