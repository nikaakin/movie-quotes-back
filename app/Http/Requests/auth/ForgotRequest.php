<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'=> ['required', 'email', 'exists:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.required', ['attribute' => __('field_names.email')]),
            'email.email' => __('validation.email', ['attribute' => __('field_names.email')]),
            'email.exists' => __('validation.exists', ['attribute' => __('field_names.email')])
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
