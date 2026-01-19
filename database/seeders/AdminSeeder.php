<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user=User::create([
            "first_name"=>"abdalrhman",
            "last_name"=>"alMuzyen",
            "email"=>"abdalrhmanalmuzyen@gmail.com",
            "password"=>Hash::make("12345678")
        ]);

        $user->assignRole("admin");
    }
}