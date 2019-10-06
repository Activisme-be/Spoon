<?php

namespace App\Http\Requests\Users;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SecurityValidator.
 */
class SecurityValidator extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'wachtwoord' => ['required', 'string', 'min:8', 'confirmed'],
            'huidig_wachtwoord' => ['required', 'string', new MatchUserPassword($this->user())],
        ];
    }
}
