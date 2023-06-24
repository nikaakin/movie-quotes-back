<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $image = str_replace('data:image/png;base64,', '', $this->image);
        $image = str_replace(' ', '+', $image);
        $this->merge([
            'image' => $image,
        ]);
    }

    public function rules(): array
    {
        return [
            'username' => 'min:3|max:15|regex:/^[a-z0-9]+$/|unique:users,username',
            'newEmail' => 'email|unique:users,email',
            'verifiedEmail' => 'email|unique:users,email',
            'email' => 'email|required|exists:users,email',
            'password' => 'min:8|max:15|regex:/^[a-z0-9]+$/',
            "google_id"=>'',
            'image'=>'string'
        ];
    }

    public function messages(): array
    {
        return [
            'username.min' => __('validation.min.string', ['attribute' => __('field_names.username'), 'min' => 3]),
            'username.max' => __('validation.max.string', ['attribute' => __('field_names.username'), 'max' => 15]),
            'username.regex' => __('validation.regex', ['attribute' => __('field_names.username')]),
            'username.unique' => __('validation.unique', ['attribute' => __('field_names.username')]),
            'newEmail.email' => __('validation.email', ['attribute' => __('field_names.email')]),
            'newEmail.unique' => __('validation.unique', ['attribute' => __('field_names.email')]),
            'verifiedEmail.email' => __('validation.email', ['attribute' => __('field_names.email')]),
            'verifiedEmail.unique' => __('validation.unique', ['attribute' => __('field_names.email')]),
            'email.required' => __('validation.required', ['attribute' => __('field_names.email')]),
            'email.email' => __('validation.email', ['attribute' => __('field_names.email')]),
            'email.exists' => __('validation.exists', ['attribute' => __('field_names.email')]),
            'password.min' => __('validation.min.string', ['attribute' => __('field_names.password'), 'min' => 8]),
            'password.max' => __('validation.max.string', ['attribute' => __('field_names.password'), 'max' => 15]),
            'password.regex' => __('validation.regex', ['attribute' => __('field_names.password')]),
            'image.image' => __('validation.image', ['attribute' => __('field_names.image')]),
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
