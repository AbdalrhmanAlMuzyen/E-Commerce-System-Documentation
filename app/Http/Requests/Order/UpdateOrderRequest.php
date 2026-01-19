<?php

namespace App\Http\Requests\order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "order_id"=>"required|integer",
            "status"=>"required|in:shipped,delivered"
        ];
    }
}
