<?php

namespace App\Http\Requests;

class UpdateModelRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Model update validation failed.';

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'string', 'max:500'],
        ];
    }
}
