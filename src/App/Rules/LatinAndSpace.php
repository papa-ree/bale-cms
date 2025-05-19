<?php

namespace Paparee\BaleCms\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LatinAndSpace implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)) {
            if (!preg_match('/^[a-zA-Z\s]+$/', $value)) {
                $fail('The :attribute may only contain letters and spaces.');
            }
        }
    }
}
