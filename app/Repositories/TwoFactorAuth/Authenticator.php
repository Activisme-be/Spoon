<?php

namespace App\Repositories\TwoFactorAuth;

use PragmaRX\Google2FALaravel\Support\Authenticator as BaseAuthenticator;

/**
 * Class Authenticator.
 */
class Authenticator extends BaseAuthenticator
{
    /**
     * Determine whether the user can pass with no OTP token.
     *
     * @return bool
     */
    protected function canPassWithoutCheckingOTP(): bool
    {
        if (empty($this->getUser()->passwordSecurity)) {
            return true;
        }

        return ! $this->getUser()->passwordSecurity->google2fa_enable
            || ! $this->isEnabled()
            || $this->noUserIsAuthenticated()
            || $this->twoFactorAuthStillValid();
    }

    /**
     * Determine wheter we can display the 2FA recovery view or not.
     * @return bool
     */
    public function canDisplayRecoveryView(): bool
    {
        return $this->isEnabled() && ! $this->isAuthenticated();
    }

    /**
     * Method for getting the Google 2FA token.
     *
     * @return string
     */
    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->passwordSecurity->{$this->config('otp_secret_column')};

        if (is_null($secret) || empty($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }
}
