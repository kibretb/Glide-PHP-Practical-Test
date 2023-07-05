<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MacAddressRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //Check size of mac address is 12 excluding the separtor characters
        if(strlen(preg_replace("/[^a-zA-Z0-9]+/", "", $value) ) != 12){
            $fail('The ' . $attribute . ' must be of size 12 characters.');
        }

        //checks the given mac address has the one uniform character as a separator
        $separator =preg_replace("/[^.\-:]+/", "", $value);
        if(count(array_count_values(str_split($separator))) != 1) {
            $fail('The ' . $attribute . ' must has same separator character.');
        }
    }
}
