<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:500',
            'description' => 'nullable|string',
            'content'     => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'discount'    => 'nullable|integer|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'hot'         => 'nullable|boolean',
            'photo'       => 'nullable|image|max:5120',
        ];
    }
}
