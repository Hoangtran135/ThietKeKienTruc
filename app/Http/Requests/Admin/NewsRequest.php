<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'hot'         => 'nullable|boolean',
            'photo'       => 'nullable|image|max:5120',
        ];
    }
}
