<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="products";
    protected $fillable = [
        "name",
        "description",
        "price",
        "category_id"
    ];
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariant::class,
        );
    }

}
