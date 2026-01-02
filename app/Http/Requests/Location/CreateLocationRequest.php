<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"=>"required|string|unique:locations,name",
            "delivery_fee"=>"required|numeric"
        ];
    }
}