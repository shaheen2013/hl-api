<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PositiveIntegerListRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // Check if all values are integer
        return collect(explode(',', $value))
            // Remove empty values
            ->filter(function ($v) {
                return strlen($v) > 0;
            })
            // Filter the integer values
            ->filter(function ($v) {
                return !(intval($v) == $v && is_numeric($v) && $v != 0);
            })
            // In case of empty, all values are integers
            ->isEmpty()
        ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Some of values on :attribute are not a valid positive integer [:input].';
    }
}
