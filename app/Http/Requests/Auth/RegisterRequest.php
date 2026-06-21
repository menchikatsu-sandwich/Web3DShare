<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;

class RegisterRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Registration validation failed.';

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9]+$/',
                'unique:users,username',
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'terms_accepted' => ['accepted'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ];
    }
}
