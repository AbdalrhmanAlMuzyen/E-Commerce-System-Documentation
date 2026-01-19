<?php

namespace App\Service;

use App\DTO\ProductVariant\CreateProductVariantDTO;
use App\DTO\ProductVariant\DeleteProductVariantDTO;
use App\DTO\ProductVariant\GetProductVariantDTO;
use App\DTO\ProductVariant\UpdateProductVariantDTO;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\ReturnTrait;
use Illuminate\Support\Facades\DB;

class ProductVariantService{
    use ReturnTrait;
    protected $productVariantRepository;
    protected $productRepository;

    public function __construct(ProductVariantRepository $productVariantRepository,ProductRepository $productRepository)
    {
        $this->productVariantRepository=$productVariantRepository;
        $this->productRepository=$productRepository;
    }

    public function createProductVariant(CreateProductVariantDTO $dto)
    {
        try{
            $product=$this->productRepository->findProductByID($dto->product_id);

            if (!$product) {
                return $this->error(false, 'Product not found',null,404);
            }

            $productVariants=$dto->product_variants;
            DB::beginTransaction();
                foreach($productVariants as $i=>$productVariant)
                {
                    $image=request()->file("product_variants.$i.image");
                    $image_url=$image->store('productVariantImages', 'public');
                    $this->productVariantRepository->createProductVariant($product,$productVariant,$image_url);
                }
            DB::commit();    
            return $this->success(true, 'Variants created successfully');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $this->error(false,$e->getMessage());
        }
    }

    public function getProudctVariants(GetProductVariantDTO $dto)
    {
        try{    
            $product=$this->productRepository->findProductByID($dto->product_id);
            if(!$product)
            {
                return $this->error(false, 'Product not found');
            }
            $productVariants=$this->productVariantRepository->getProudctVariants($product);

            if($productVariants->isEmpty())
            {
                return $this->error(false, 'Product variants not found',[],404);
            }

            return $this->success(true,"Product varaints retrieved successfully",$productVariants);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function deleteProductVariant(DeleteProductVariantDTO $dto)
    {
        try{
            $productVariant=$this->productVariantRepository->findProductVariantByID($dto->product_variant_id);
            if(!$productVariant)
            {
                return $this->error(false,"product variant not found",null,404);
            }

            $this->productVariantRepository->deleteProductVariant($productVariant);

            return $this->success(true,"Product variant deleted successfully",null,200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function updateProductVariant(UpdateProductVariantDTO $dto)
    {
        try{
            $productVariant=$this->productVariantRepository->findProductVariantByID($dto->product_variant_id);
            if(!$productVariant)
            {
                return $this->error(false,"product variant not found",null,404);
            }
            
            $this->productVariantRepository->updateProductVariant($productVariant,$dto->total_stock);

            return $this->success(true,"Product variant updated successfully",$productVariant->fresh(),200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    
    
}