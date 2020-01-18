<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateValidator.
 *
 * @package App\Http\Requests\Users
 */
class InformationValidator extends FormRequest
{
    /**
     * Method for the additional validation rules for an POST request.
     *
     * @return array
     */
    protected function getPostRules(): array
    {
        return ['email' => ['required', 'string', 'email', 'max:191', 'unique:users']];
    }

    /**
     * Method for additional validation rules for an PATCH request.
     *
     * @return array
     */
    public function getPatchRules(): array
    {
        return ['email' => ['required', 'string', 'email', 'max:191', 'unique:users,email,'.$this->user()->id]];
    }

    /**
     * The basic validation rules for the request that applies to all methods.
     *
     * @return array
     */
    protected function baseRules(): array
    {
        return [
            'voornaam'   => ['required', 'string', 'max:191'],
            'achternaam' => ['required', 'string', 'max:191'],
            'role'       => ['required|array|min:1'],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->getMethod()) {
            case 'POST':  $methodRules = $this->getPostRules();     break;
            case 'PATCH': $methodRules = $this->getPatchRules();    break;

            // The method is not found so there are no additional validation rules needed.
            default: $methodRules = [];
        }

        return array_merge($this->baseRules(), $methodRules);
    }
}
