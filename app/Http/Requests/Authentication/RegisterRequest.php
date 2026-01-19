<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "email"=>"required|string|email|ends_with:gmail.com|unique:users",
            "password"=>"required|string|min:6",
            "location_id"=>"required|integer|exists:locations,id",
            "address_details"=>"required|string",
            "building_number"=>"required|integer",
            "device_id"=>"required|string"
        ];
    }
}