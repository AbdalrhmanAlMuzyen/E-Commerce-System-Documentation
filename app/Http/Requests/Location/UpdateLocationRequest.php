<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "location_id"=>"required|integer",
            "name"=>"nullable|string|unique:locations,name",
            "delivery_fee"=>"nullable|numeric"
        ];
    }
}
