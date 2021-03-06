<?php

namespace App\Repositories\TwoFactorAuth;

use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use PragmaRX\Google2FALaravel\Google2FA;
use RuntimeException;

/**
 * Class Repository
 *
 * @package App\Repositories\TwoFactorAuth
 */
class Repository
{
    protected Guard $auth;
    protected RecoveryRepository $recoveryRepository;

    public function __construct(Guard $auth, RecoveryRepository $recoveryRepository)
    {
        $this->auth = $auth;
        $this->recoveryRepository = $recoveryRepository;
    }

    /**
     * Helper function for getting the authenticated user.
     */
    public function getAuthenticatedUser(): Authenticatable
    {
        if (! $this->auth->check()) {
            // There is no authenticated user found in the application.
            // So We can't run any functions that relay on the authenticated user.
            throw new RuntimeException('No authenticated user.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->auth->user();
    }

    /**
     * Method for the underlying Google 2FA layer.
     */
    public function google2FaLayer(): Google2FA
    {
        return app('pragmarx.google2fa');
    }

    /**
     * Get the url for the google 2FA system.
     */
    public function getGoogle2FaUrl(): string
    {
        $user = $this->getAuthenticatedUser();
        $google2FaUrl = '';

        if ($user->twoFactorAuthentication()->exists()) {
            $google2fa = app('pragmarx.google2fa');
            $google2FaUrl = $google2fa->getQRCodeInline(config('app.name'), $user->email, $user->twoFactorAuthentication->google2fa_secret);
        }

        return $google2FaUrl;
    }

    /**
     * Method for registering the 2FA secret key in the database.
     *
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     */
    public function createSecretKey(): TwoFactorAuthentication
    {
        return TwoFactorAuthentication::create([
            'user_id' => $this->getAuthenticatedUser()->id,
            'google2fa_secret' => $this->google2FaLayer()->generateSecretKey(),
            'google2fa_recovery_tokens' => $this->recoveryRepository->generateTokens(),
            'google2fa_enable' => 0,
        ]);
    }

    /**
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function canEnable2Fa(User $user, string $verificationCode): bool
    {
        return $this->google2FaLayer()->verifyKey($user->twoFactorAuthentication->google2fa_secret, $verificationCode);
    }
}
