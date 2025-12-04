<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    /**
     * @return array<string,
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'buy_price'     => 'required|numeric|min:0',
            'sell_price'    => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
            'unit'          => 'required|in:pcs,box,kg,liter',
            'location'      => 'nullable|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description'   => 'nullable|string',
        ];
    }
}
