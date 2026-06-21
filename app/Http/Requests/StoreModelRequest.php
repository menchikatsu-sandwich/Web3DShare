<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

class StoreModelRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Upload validation failed.';

    public function rules(): array
    {
        $limits = config('web3dshare.limits');

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'tags' => ['nullable', 'string', 'max:500'],
            'model' => [
                'required',
                'file',
                'max:'.$limits['model_upload_kb'],
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $value->isValid()) {
                        $fail('The model upload did not complete. Please choose the file again.');

                        return;
                    }

                    if (strtolower($value->getClientOriginalExtension()) !== 'glb') {
                        $fail('The model must be a .glb file.');
                    }
                },
            ],
            'thumbnail' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:'.$limits['thumbnail_upload_kb'],
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $value->isValid()) {
                        $fail('The thumbnail upload did not complete. Please choose the image again.');
                    }
                },
            ],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        Log::warning('Model upload validation failed', [
            'user_id' => $this->user()?->id,
            'errors' => $validator->errors()->toArray(),
            'model_file' => $this->file('model')?->getClientOriginalName(),
            'model_size' => $this->file('model')?->getSize(),
            'thumbnail_file' => $this->file('thumbnail')?->getClientOriginalName(),
            'thumbnail_size' => $this->file('thumbnail')?->getSize(),
            'category_id' => $this->input('category_id'),
        ]);

        parent::failedValidation($validator);
    }
}
