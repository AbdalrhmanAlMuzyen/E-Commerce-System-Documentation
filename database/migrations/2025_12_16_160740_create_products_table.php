<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained("categories")->onDelete("cascade")->index();
            $table->string("name");
            $table->text("description");
            $table->decimal("price",8,2);
            $table->timestamps();
        });
    }
};
