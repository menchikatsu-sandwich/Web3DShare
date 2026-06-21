<?php

namespace App\Http\Requests;

class UpdateProfileRequest extends ApiFormRequest
{
    protected string $validationMessage = 'Profile update validation failed.';

    public function rules(): array
    {
        return [
            'nickname' => ['nullable', 'string', 'max:100'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:'.config('web3dshare.limits.profile_image_upload_kb'),
            ],
        ];
    }
}
