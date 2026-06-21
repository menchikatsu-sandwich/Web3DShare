<?php

namespace App\Http\Requests;

class StoreCommentRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Comment validation failed.';

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];
    }
}
