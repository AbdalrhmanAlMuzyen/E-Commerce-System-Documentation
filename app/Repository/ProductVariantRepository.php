<?php
namespace App\Repository;

use App\Models\ProductVariant;

class ProductVariantRepository{

    public function createProductVariant($product, array $productVariant,$image_url)
    {
        return $product->productVariants()->create([
            'size_id'     => $productVariant['size_id'],
            'color_id'    => $productVariant['color_id'],
            'total_stock' => $productVariant['total_stock'],
            'image'       => $image_url,
        ]);
    }

    public function getProudctVariants($product)
    {
        return $product->productVariants()->selectRaw("product_variants.*,(CASE WHEN (total_stock - total_reserved_stock) > 0 THEN 'available' ELSE 'not available' END) AS is_available")->with(["color","size"])->get();
    }

    public function findProductVariantByID($product_variant_id)
    {
        return ProductVariant::find($product_variant_id);
    }

    public function deleteProductVariant($productVariant)
    {
        return $productVariant->delete();
    }

    public function updateProductVariant($productVariant,$total_stock)
    {   
        return $productVariant->update([
            "total_stock"=>$total_stock
        ]);
    }
}