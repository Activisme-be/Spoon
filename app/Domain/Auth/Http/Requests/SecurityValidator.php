<?php

namespace App\Domain\Auth\Http\Requests;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SecurityValidator
 *
 * @package App\Http\Requests\Users
 */
class SecurityValidator extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'wachtwoord' => ['required', 'string', 'min:8', 'confirmed'],
            'huidig_wachtwoord' => ['required', 'string', new MatchUserPassword($this->user())],
        ];
    }
}
