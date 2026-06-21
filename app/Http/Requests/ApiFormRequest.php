<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Keeps validation responses consistent between the browser and public API.
 *
 * Web forms retain Laravel's normal redirect-and-errors behavior. API and
 * AJAX clients receive the same { message, errors } shape as controllers.
 */
abstract class ApiFormRequest extends FormRequest
{
    protected string $validationMessage = 'The submitted data is invalid.';

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->is('api/*') || $this->wantsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => $this->validationMessage,
                'errors' => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
