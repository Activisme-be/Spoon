<?php

namespace App\Http\Requests\Users;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use App\Rules\MatchRecoveryCode;
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
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->twoFactorAuthentication()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return ['recovery_token' => ['required', 'string', new MatchRecoveryCode($this->user())]];
    }
}
