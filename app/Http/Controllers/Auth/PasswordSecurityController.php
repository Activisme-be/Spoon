<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\TwoFactorDisableRequest;
use App\Notifications\Users\TwoFactor\DisabledNotification;
use App\Notifications\Users\TwoFactor\EnabledNotification;
use App\Repositories\TwoFactorAuth\Repository as TwoFactorAuthRepository;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class PasswordSecurityController
 *
 * @package App\Http\Controllers\Auth
 */
class PasswordSecurityController extends Controller
{
    private TwoFactorAuthRepository $twoFactorAuthRepository;

    public function __construct(TwoFactorAuthRepository $twoFactorAuthRepository)
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user']);
        $this->twoFactorAuthRepository = $twoFactorAuthRepository;
    }

    public function generate2fasecret(): RedirectResponse
    {
        $route = redirect()->route('account.security');

        try {
            $this->twoFactorAuthRepository->createSecretKey();
            return $route->with('success', 'De unieke 2FA sleutel is gegenereerd voor uw account. Verifieer de sleutel om 2FA te activeren.');
        } catch (IncompatibleWithGoogleAuthenticatorException $exception) {
            return $route->with('error', 'De authenticator app is niet compatible met Google Authenticator.');
        } catch (InvalidCharactersException $exception) {
            return $route->with('error', 'The authenticator code bevat invalide karakter.');
        }
    }

    /**
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function enable2fa(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($this->twoFactorAuthRepository->canEnable2Fa($user, $request->get('verify-code'))) {
            $user->twoFactorAuthentication->update(['google2fa_enable' => true]);
            $user->notify(new EnabledNotification($user->twoFactorAuthentication->google2fa_recovery_tokens));

            auth()->logout();
            session()->flash('status', '2FA is geactiveerd! Ook hebben wij je recovery codes toegestuurd per mail.');

            return redirect()->route('account.security');
        }

        return redirect()->route('account.security')->with('error', 'Invalide verificatie code, Probeer het opnieuw!');
    }

    public function disable2fa(TwoFactorDisableRequest $request): RedirectResponse
    {
        $request->user()->twoFactorAuthentication->delete();
        $request->user()->notify(new DisabledNotification());

        return redirect()->route('account.security')->with('success', '2FA is gedeactiveerd.');
    }
}
