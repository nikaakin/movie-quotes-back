<?php

namespace App\Http\Requests\movies;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->movie->user_id === auth()->user()->id;
    }
}
