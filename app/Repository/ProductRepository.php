<?php

namespace App\Repository;

use App\DTO\Product\CreateProductDTO;
use App\Models\Product;

class ProductRepository{

    public function createProduct($category,CreateProductDTO $dto)
    {
        return $category->products()->create([
            "name"=>$dto->name,
            "description"=>$dto->description,
            "price"=>$dto->price
        ]);
    }
    
    public function findProductByID($product_id)
    {
        return Product::find($product_id);
    }

    public function deleteProduct($product)
    {
        return $product->delete();
    }

    public function updateProduct($product, $data)
    {
        return $product->update($data);
    }
}