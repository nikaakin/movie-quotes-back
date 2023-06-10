<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:15|regex:/^[a-z0-9]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => __('validation.required', ['attribute' => __('field_names.token')]),
            'token.string' => __('validation.string', ['attribute' => __('field_names.token')]),
            'email.required' => __('validation.required', ['attribute' => __('field_names.email')]),
            'email.email' => __('validation.email', ['attribute' => __('field_names.email')]),
            'password.required' => __('validation.required', ['attribute' => __('field_names.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('field_names.password'), 'min' => 8]),
            'password.max' => __('validation.max.string', ['attribute' => __('field_names.password'), 'max' => 15]),
            'password.regex' => __('validation.regex', ['attribute' => __('field_names.password')]),
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
