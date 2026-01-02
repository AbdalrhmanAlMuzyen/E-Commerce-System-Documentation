<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade")->index();
            $table->foreignId("user_location_id")->constrained("user_locations")->onDelete("cascade")->index();
            $table->decimal("total_price",8,2);
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled', 'failed'])->default('pending');
            $table->timestamps();
        });
    }
};
