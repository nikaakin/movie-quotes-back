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
        $current_record = null;
        if($id) {
            $current_record = DB::table($this->table)->where('id', $id)->first();
        }

        $records = DB::table($this->table)->where(function ($query) use ($value) {
            $query
                ->whereRaw("JSON_EXTRACT(" . $this->column . ", '$.$this->language') = ?", [$value]);
        })->get();

        if($records->count() > 0) {
            if($current_record) {
                $already_exists = $records->firstWhere('id', '!=', $current_record->id);
                if($already_exists) {
                    $fail(__('validation.unique', ['attribute' => __('field_names.title')]));
                }
            } else {
                $fail(__('validation.unique', ['attribute' => __('field_names.title')]));
            }
        }
    }
}
