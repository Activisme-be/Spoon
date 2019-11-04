<?php

namespace App\Http\Requests\Users;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LockValidator
 *
 * @package App\Http\Requests\Users
 */
class LockValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('deactivate-user', $this->userEntity);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'reden'      => ['required', 'string'],
            'wachtwoord' => ['required', 'string', new MatchUserPassword($this->user())],
        ];
    }
}
