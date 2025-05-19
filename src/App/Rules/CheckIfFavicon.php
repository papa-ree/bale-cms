<?php

namespace Paparee\BaleCms\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckIfFavicon implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value->getClientOriginalExtension() !== 'ico') {
            $fail('The :attribute must be ico file.');
        }
    }
}
