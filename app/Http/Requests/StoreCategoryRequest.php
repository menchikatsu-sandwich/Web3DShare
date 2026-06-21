<?php

namespace App\Http\Requests;

class StoreCategoryRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Category validation failed.';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ];
    }
}
