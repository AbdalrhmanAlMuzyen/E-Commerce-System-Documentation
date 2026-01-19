<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductFromCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "cart_item_id"=>"required|integer|exists:cart_items,id",
            "quantity"=>"required|integer|between:1,5"
        ];
    }
}