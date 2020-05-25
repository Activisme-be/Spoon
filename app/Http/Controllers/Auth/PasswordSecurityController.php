<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        try { // To generate the secret authenticator key.
            $this->twoFactorAuthRepository->createSecretKey();

            return $route->with('success', 'De unieke 2FA sleutel is gegenereerd voor uw account. Verifieer de sleutel om 2FA te activeren.');
        } catch (IncompatibleWithGoogleAuthenticatorException $exception) {
            // Return the user back with an error flash message.
            // When the authenticator isn't compatible with google authenticator app.

            return $route->with('error', 'De authenticator app is niet compatible met Google Authenticator.');
        } catch (InvalidCharactersException $exception) {
            // Return back with an error flash message.
            // When the authenticator code has invalid characters.

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
        $repositoryLayer = $this->twoFactorAuthRepository;
        $user = $repositoryLayer->getAuthenticatedUser();
        $secret = $request->get('verify-code');

        if ($repositoryLayer->google2FaLayer()->verifyKey($user->twoFactorAuthentication->google2fa_secret, $secret)) {
            $user->twoFactorAuthentication->update(['google2fa_enable' => true]);

            return redirect()->route('account.security')->with('success', '2Fa is geactiveerd! Ook hebben wij je recovery codes toegestuurd per mail.');
        }

        return redirect()->route('account.security')->with('error', 'Invalide verificatie code, Probeer het opnieuw!');
    }

    public function disable2fa(Request $request): RedirectResponse
    {
        if (! Hash::check($request->get('current-password'), $request->user()->password)) {
            return back()->with('error', 'Het gegeven wachtwoord klopt niet met uw huidige wachtwoord. Probeer het opnieuw.');
        }

        $validatedData = $request->validate(['current-password' => 'required']);

        $request->user()->twoFactorAuthentication->update(['google2fa_enable' => false]);
        $request->user()->twoFactorAuthentiction->delete();

        return redirect()->route('account.security')->with('success', '2FA is gedeactiveerd.');
    }
}
