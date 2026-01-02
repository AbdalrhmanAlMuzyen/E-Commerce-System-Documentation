<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade")->index();
            $table->foreignId("location_id")->constrained("locations")->onDelete("cascade")->index();
            $table->string("address_details");
            $table->integer("building_number");
            $table->timestamps();
        });
    }
};