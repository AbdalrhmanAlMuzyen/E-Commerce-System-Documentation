<?php

namespace App\Service;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\DeleteProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\ReturnTrait;

class ProductService{
    protected $productRepository;
    protected $categoryRepository;
    use ReturnTrait;

    public function __construct(ProductRepository $productRepository,CategoryRepository $categoryRepository)
    {
        $this->productRepository=$productRepository;
        $this->categoryRepository=$categoryRepository;
    }

    public function createProduct(CreateProductDTO $dto)
    {
        try{
            $category=$this->categoryRepository->findCategoryByID($dto->category_id);
            if(!$category)
            {
                return $this->error(false,"cateogry not found",null,404);
            }
            $product=$this->productRepository->createProduct($category,$dto);
            return $this->success(true,"Product created successfully",$product,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function deleteProduct(DeleteProductDTO $dto)
    {
        try{
            $product=$this->productRepository->findProductByID($dto->product_id);
            if(!$product)
            {
                return $this->error(false,"Product not found",null,404);
            }
            $this->productRepository->deleteProduct($product);

            return $this->success(true,"Product deleted successfully",null,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function updateProduct(UpdateProductDTO $dto)
    {
        try{
            $product=$this->productRepository->findProductByID($dto->product_id);
            if(!$product)
            {
                return $this->error(false,"Product not found",null,404);
            }
            
            $data=collect([
                "name"=>$dto->name,
                "description"=>$dto->description,
                "price"=>$dto->price
            ])->filter(function($value){
                return !is_null($value);
            })->toArray();

            if(empty($data))
            {
               return $this->error(false,"No changes were made",[],400);
            }
            $this->productRepository->updateProduct($product,$data);
            return $this->success(true,"Product updated successfully",$product->fresh());
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

}