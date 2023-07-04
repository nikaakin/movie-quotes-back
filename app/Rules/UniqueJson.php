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
        $id = request()->segment(count(request()->segments()));
        $current_movie = DB::table($this->table)->where('id', $id)->first();
        $movie = DB::table($this->table)->where(function ($query) use ($value) {
            $query
                ->whereRaw("JSON_EXTRACT(" . $this->column . ", '$.$this->language') = ?", [$value]);
        })->first();

        if($movie && $movie->id != $current_movie->id) {
            $fail(__('validation.unique', ['attribute' => __('field_names.title')]));
        }
    }
}
