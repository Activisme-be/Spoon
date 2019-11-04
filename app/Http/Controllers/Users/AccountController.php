<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\InformationValidator;
use App\Http\Requests\Users\SecurityValidator;
use App\Repositories\TwoFactorAuth\Repository as TwoFactorAuthRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Class AccountController
 *
 * @package App\Http\Controllers\Users
 */
class AccountController extends Controller
{
    /**
     * 2FA Repository variable.
     *
     * @var TwoFactorAuthRepository
     */
    private $twoFactorAuthRepository;

    /**
     * AccountController constructor.
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
     * Method for displaying the account settings view. (info).
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('users.settings.information');
    }

    /**
     * Method for displaying the account settings view. (Security).
     * @return Renderable
     */
    public function indexSecurity(): Renderable
    {
        $google2faUrl = $this->twoFactorAuthRepository->getGoogle2FaUrl();

        return view('users.settings.security', compact('google2faUrl'));
    }

    /**
     * Method for updating the account information from the authenticated user.
     *
     * @param  InformationValidator $input The form request class that handles the validation.
     * @return RedirectResponse
     */
    public function updateInformation(InformationValidator $input): RedirectResponse
    {
        if ($this->getAuthenticatedUser()->update($input->all())) { // Update confirmation
            flash('Uw accunt informatie is met success aangepast.')->success()->important();
        }

        return redirect()->back(); // HTTP 302 - Redirect
    }

    /**
     * Method for update the account security from the authenticated used.
     *
     * @param  SecurityValidator $request The instance that holds all the request information.
     * @return RedirectResponse
     */
    public function updateSecurity(SecurityValidator $request): RedirectResponse
    {
        if ($this->getAuthenticatedUser()->update(['password' => $request->wachtwoord])) {
            auth()->logoutOtherDevices($request->huiding_wachtwoord);
            flash('Uw account beveiliging is met success aangepast.')->success()->important();
        }

        return redirect()->back();
    }
}
