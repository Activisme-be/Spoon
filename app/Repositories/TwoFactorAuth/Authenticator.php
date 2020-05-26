<?php

namespace App\Repositories\TwoFactorAuth;

use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Support\Authenticator as BaseAuthenticator;

/**
 * Class Authenticator
 *
 * @package App\Repositories\TwoFactorAuth
 */
class Authenticator extends BaseAuthenticator
{
    protected function canPassWithoutCheckingOTP(): bool
    {
        if (empty($this->getUser()->twoFactorAuthentication)) {
            return true;
        }

        return ! $this->getUser()->twoFactorAuthentication->google2fa_enable
            || ! $this->isEnabled()
            || $this->noUserIsAuthenticated()
            || $this->twoFactorAuthStillValid();
    }

    public function canDisplayRecoveryView(): bool
    {
        return $this->isEnabled() && ! $this->isAuthenticated();
    }

    /**
     * Method for getting the Google 2FA token.
     *
     * @throws InvalidSecretKey
     */
    protected function getGoogle2FASecretKey(): string
    {
        $secret = $this->getUser()->twoFactorAuthentication->{$this->config('otp_secret_column')};

        if ($this->hasAuthenticationSecret($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }

    public function hasAuthenticationSecret(?string $secret): ?String
    {
        return $secret === null || empty($secret);
    }
}
