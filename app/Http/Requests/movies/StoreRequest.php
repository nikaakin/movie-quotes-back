<?php

namespace App\Http\Requests\movies;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
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
                'title.en' => "required|unique:movies,title->en",
                'title.ka' => "required|unique:movies,title->ka",
                'director.en' => "required",
                'director.ka' => "required",
                'description.en' => "required",
                'description.ka' => "required",
                'image'=> "required",
                'year' => "required",
                'user_id' => 'required|exists:users,id',
            ];
        }

        public function messages(): array
        {
            return [
                'title.en.required' =>__('validation.required', ['attribute' => __('field_names.title')]),
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
                'user_id.exists' => __('validation.exists', ['attribute' => __('field_names.user')])
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
