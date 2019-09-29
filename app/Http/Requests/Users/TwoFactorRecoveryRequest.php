<?php

namespace App\Http\Requests\Users;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TwoFactorRecoveryRequest
 *
 * @package App\Http\Requests\Users
 */
class TwoFactorRecoveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->passwordSecurity()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['wachtwoord' => ['required', 'string', new MatchUserPassword($this->user())]];
    }
}
