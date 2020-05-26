<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class MatchRecoveryCode
 *
 * @package App\Rules
 */
class MatchRecoveryCode implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        //
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
