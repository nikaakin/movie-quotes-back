<?php

namespace App\Http\Requests\quotes;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->quote->user_id === auth()->user()->id;
    }
}
