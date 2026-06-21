<?php

namespace App\Http\Requests;

class StoreVerificationRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Verification request validation failed.';

    public function rules(): array
    {
        return [
            'note' => ['required', 'string', 'max:2000'],
        ];
    }
}
