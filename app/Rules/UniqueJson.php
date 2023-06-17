<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueJson implements ValidationRule
{
    public function __construct(public string $language, public string $table, public string $column)
    {
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $movie = DB::table($this->table)->where(function ($query) use ($value) {
            $query
                ->whereRaw("JSON_EXTRACT(" . $this->column . ", '$.$this->language') = ?", [$value]);
        })->first();

        if($movie) {
            $fail(__('validation.unique', ['attribute' => __('field_names.title')]));
        }
    }
}
