<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     *
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'manager']);
    }

    /**
     * @return array<string,
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
