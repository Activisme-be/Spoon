<?php

namespace App\Repositories\TwoFactorAuth;

use App\Models\PasswordSecurity;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use PragmaRX\Google2FALaravel\Google2FA;
use RuntimeException;

/**
 * Class Repository.
 */
class Repository
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Repository Constructor.
     *
     * @param  Guard $auth The variable for mapping hte authentication guard.
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Helper function for getting the authenticated user.
     *
     * @return User
     */
    public function getAuthenticatedUser(): User
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
     *
     * @return Google2FA
     */
    public function google2FaLayer(): Google2FA
    {
        return app('pragmarx.google2fa');
    }

    /**
     * Get the url for the google 2FA system.
     *
     * @return string
     */
    public function getGoogle2FaUrl(): string
    {
        $user = $this->getAuthenticatedUser();
        $google2FaUrl = '';

        if ($user->passwordSecurity()->exists()) {
            $google2fa = app('pragmarx.google2fa');
            $google2fa->setAllowInsecureCallToGoogleApis(true);

            $google2FaUrl = $google2fa->getQRCodeGoogleUrl(config('app.name'), $user->email, $user->passwordSecurity->google2fa_secret);
        }

        return $google2FaUrl;
    }

    /**
     * Method for registering the 2FA secret key in the database.
     *
     * @return PassWordSecurity
     */
    public function createSecretKey(): PasswordSecurity
    {
        return PasswordSecurity::create([
            'user_id' => $this->getAuthenticatedUser()->id,
            'google2fa_secret' => $this->google2FaLayer()->generateSecretKey(),
            'google2FA_enable' => 0,
        ]);
    }
}
