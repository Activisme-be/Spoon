<?php

namespace App\Http\Requests\Users;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TwoFactorDisableRequest
 *
 * @package App\Http\Requests\Users
 */
class TwoFactorDisableRequest extends FormRequest
{
    public function rules(): array
    {
        return ['current-password' => ['required', new MatchUserPassword($this->user())]];
    }
}
