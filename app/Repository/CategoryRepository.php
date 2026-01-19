<?php

namespace App\Repository;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\ShowCategoryDTO;
use App\Models\Category;

class CategoryRepository{
    
    public function createCategory(CreateCategoryDTO $dto)
    {
        $image=request()->file("image");
        $image_url=$image->store("categoryImages","public");
        return Category::create([
            "name"=>$dto->name,
            "image"=>$image_url
        ]);
    }

    public function deleteCategory($category)
    {
        return $category->delete();
    }

    public function getCategories()
    {
        return Category::withCount("products AS products_count")->orderBy("products_count","DESC")->get();
    }

    public function findCategoryByID($category_id)
    {
        return Category::find($category_id);
    }

    public function showCategory($category)
    {
        return $category->products()
                        ->leftJoin('product_variants', 'product_variants.product_id', '=', 'products.id')
                        ->leftJoin('order_items', 'order_items.product_variant_id', '=', 'product_variants.id')
                        ->leftJoin("orders",function($join){
                            $join->on('orders.id', '=', 'order_items.order_id')->whereIn('orders.status', ['paid', 'shipped', 'delivered'])->where("orders.created_at",">=",now()->subDays(20));
                        })
                        ->selectRaw("products.* , IFNULL ( SUM(order_items.quantity) , 0) as sales_last_20_days,IFNULL( SUM(product_variants.total_stock) ,0) as total_stock")
                        ->groupBy("id","name","description","price","category_id","created_at","updated_at")
                        ->orderBy("sales_last_20_days","DESC")->orderBy("total_stock","DESC")
                        ->get();
    }
}