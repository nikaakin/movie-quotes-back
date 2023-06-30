<?php

namespace App\Http\Requests\quotes;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quote.en' => "required|regex:/^[A-Za-z\s]+$/",
            'quote.ka' => "required|regex:/^[ა-ჰ\s]+$/",
            'image'=> "required|image",
            'movie_id' => 'required|exists:movies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'quote.en.required' =>__('validation.required', ['attribute' => __('field_names.quote')]),
            'quote.ka.required' => __('validation.required', ['attribute' => __('field_names.quote')]),
            'image.required' => __('validation.required', ['attribute' => __('field_names.image')]),
            'movie_id.required' => __('validation.required', ['attribute' => __('field_names.movie')]),
            'movie_id.exists' => __('validation.exists', ['attribute' => __('field_names.movie')])
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
