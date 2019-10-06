<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\TwoFactorAuth\Repository as TwoFactorAuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class PasswordSecurityController.
 *
 * @todo Refactoring the controller.
 */
class PasswordSecurityController extends Controller
{
    /**
     * 2FA Repository variable.
     *
     * @var TwoFactorAuthRepository
     */
    private $twoFactorAuthRepository;

    /**
     * AccountConstroller constructor.
     *
     * @param  TwoFactorAuthRepository $twoFactorAuthRepository 2fa method layer.
     * @return void
     */
    public function __construct(TwoFactorAuthRepository $twoFactorAuthRepository)
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user']);
        $this->twoFactorAuthRepository = $twoFactorAuthRepository;
    }

    /**
     * Method for generating the 2Fa secret key.
     *
     * @return RedirectResponse
     */
    public function generate2fasecret(): RedirectResponse
    {
        $this->twoFactorAuthRepository->createSecretKey();

        return redirect()->route('account.security')
            ->with('success', 'De unieke 2FA sleutel is gegenereerd voor uw account. Verifieer de sleutel om 2FA te activeren.');
    }

    /**
     * Method for activating 2FA on the authenticated user.
     *
     * @param  Request $request The form request class that contains all the request POST data.
     * @return RedirectResponse
     */
    public function enable2fa(Request $request): RedirectResponse
    {
        $repositoryLayer = $this->twoFactorAuthRepository;
        $user = $repositoryLayer->getAuthenticatedUser();
        $secret = $request->get('verify-code');

        if ($repositoryLayer->google2FaLayer()->verifyKey($user->passwordSecurity->google2fa_secret, $secret)) {
            $user->passwordSecurity->update(['google2fa_enable' => true]);
            $this->twoFactorAuthRepository->generateRecoveryCodes($request);

            return redirect()->route('account.security')->with('success', '2Fa is geactiveerd! Ook hebben wij je recovery codes toegestuurd per mail.');
        }

        return redirect()->route('account.security')->with('error', 'Invalide verificatie code, Probeer het opnieuw!');
    }

    /**
     * Method for disabling the 2Fa system from the authentiated user.
     *
     * @param  Request $request  The form request class that contains all the request POST data
     * @return RedirectResponse
     */
    public function disable2fa(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! (Hash::check($request->get('current-password'), $user->password))) {
            return back()->with('error', 'Het gegeven wachtwoord klopt niet met uw huidige wachtwoord. Probeer het opnieuw.');
        }

        $validatedData = $request->validate(['current-password' => 'required']);

        // $user->passwordSecurity->update(['google2fa_enable' => false]);
        $user->passwordSecurity->delete();

        return redirect()->route('account.security')
            ->with('success', '2FA is gedeactiveerd.');
    }
}
