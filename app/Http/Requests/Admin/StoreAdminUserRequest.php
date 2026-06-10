<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:500',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
