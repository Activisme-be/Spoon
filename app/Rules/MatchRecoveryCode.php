<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class MatchRecoveryCode
 *
 * @package App\Rules
 */
class MatchRecoveryCode implements Rule
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value)
    {
        return in_array($value, $this->user->twoFactorAuthentication->google2fa_recovery_tokens, true);
    }

    public function message()
    {
        return 'De opgegeven 2FA recovery code kon niet worden gevonden.';
    }
}
