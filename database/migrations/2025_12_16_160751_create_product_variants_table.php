<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained("products")->onDelete("cascade")->index();
            $table->foreignId("size_id")->constrained("sizes")->onDelete("cascade")->index();
            $table->foreignId("color_id")->constrained("colors")->onDelete("cascade")->index();
            $table->integer("total_stock")->default(0);
            $table->integer("total_reserved_stock")->default(0);
            $table->string("image");
            $table->timestamps();
        });
    }
};
