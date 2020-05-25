<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\TwoFactorDisableRequest;
use App\Repositories\TwoFactorAuth\Repository as TwoFactorAuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class PasswordSecurityController.
 *
 * @todo Refactoring the controller.
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
        if ($this->twoFactorAuthRepository->google2FaLayer()->verifyKey($request->user()->twoFactorAuthentication->google2fa_secret, $request->get('verify-code'))) {
            $request->user()->twoFactorAuthentication->update(['google2fa_enable' => true]);

            return redirect()->route('account.security')->with('success', '2Fa is geactiveerd! Ook hebben wij je recovery codes toegestuurd per mail.');
        }

        return redirect()->route('account.security')->with('error', 'Invalide verificatie code, Probeer het opnieuw!');
    }

    public function disable2fa(TwoFactorDisableRequest $request): RedirectResponse
    {
        $request->user()->twoFactorAuthentication->delete();

        return redirect()->route('account.security')->with('success', '2FA is gedeactiveerd.');
    }
}
