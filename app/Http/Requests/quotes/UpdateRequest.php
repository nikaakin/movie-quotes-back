<?php

namespace App\Http\Requests\quotes;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->id === $this->quote->user_id;
    }

    public function rules(): array
    {
        return [
            'quote.en' => "regex:/^[A-Za-z\s]+$/",
            'quote.ka' => "regex:/^[ა-ჰ\s]+$/",
            'image'=> "image",
        ];
    }

    public function messages(): array
    {
        return [
            'quote.en.regex' =>__('validation.regex', ['attribute' => __('field_names.quote')]),
            'quote.ka.regex' => __('validation.regex', ['attribute' => __('field_names.quote')]),
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
