<?php

namespace App\Http\Requests\movies;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title.en' => ["required" ,"regex:/^[A-Za-z0-9 ,!'\s]+$/" ],
            'title.ka' => ["required" ,"regex:/^[ა-ჰ0-9 ,!'\s]+$/"],
            'director.en' => "required|regex:/^[A-Za-z\s]+$/",
            'director.ka' => "required|regex:/^[ა-ჰ\s]+$/",
            'description.en' => "required|regex:/^[A-Za-z0-9 ,!'\s]+$/",
            'description.ka' => "required|regex:/^[ა-ჰ0-9 ,!'\s]+$/",
            'image' => "required|image",
            'year' => "required",
            'genres' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.en.required' => __('validation.required', ['attribute' => __('field_names.title')]),
            'title.en.unique' => __('validation.unique', ['attribute' => __('field_names.title')]),
            'title.ka.required' => __('validation.required', ['attribute' => __('field_names.title')]),
            'title.ka.unique' => __('validation.unique', ['attribute' => __('field_names.title')]),
            'director.en.required' => __('validation.required', ['attribute' => __('field_names.director')]),
            'director.ka.required' => __('validation.required', ['attribute' => __('field_names.director')]),
            'description.en.required' => __('validation.required', ['attribute' => __('field_names.description')]),
            'description.ka.required' => __('validation.required', ['attribute' => __('field_names.description')]),
            'image.required' => __('validation.required', ['attribute' => __('field_names.image')]),
            'year.required' => __('validation.required', ['attribute' => __('field_names.year')]),
            'user_id.required' => __('validation.required', ['attribute' => __('field_names.user')]),
            'user_id.exists' => __('validation.exists', ['attribute' => __('field_names.user')]),
            'genres.required' => __('validation.required', ['attribute' => __('field_names.genres')]),
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
