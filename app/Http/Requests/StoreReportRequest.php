<?php

namespace App\Http\Requests;

class StoreReportRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Report validation failed.';

    public function rules(): array
    {
        return [
            'reason' => [
                'required',
                'string',
                'in:stolen_content,inappropriate_content,spam,broken_file,wrong_category,other',
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
