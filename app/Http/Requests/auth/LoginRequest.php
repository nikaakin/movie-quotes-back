<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "username" => "required|min:3",
            "password"=> "required",
            "remember" => "boolean"
        ];

    }

    public function messages(): array
    {
        return [
            "username.required" => __('validation.required', ['attribute' => __('field_names.username')]),
            "username.min" => __("validation.min.string", ["attribute" => __('field_names.username'), "min" => 3]),
            "password.required" => __('validation.required', ['attribute' => __('field_names.password')]),
            "remember_me.boolean" => __('validation.boolean', ['attribute' => __('field_names.remember_me')]),
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
