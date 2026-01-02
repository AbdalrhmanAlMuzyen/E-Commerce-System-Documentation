<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique(); //EX : Bab toma
            $table->decimal("delivery_fee",8,2);
            $table->timestamps();
        });
    }
};