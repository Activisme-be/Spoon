<?php

namespace App\Domain\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Auth\Http\Requests\InformationValidator;
use App\Domain\Auth\Http\Requests\SecurityValidator;
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
    private TwoFactorAuthRepository $twoFactorAuthRepository;

    public function __construct(TwoFactorAuthRepository $twoFactorAuthRepository)
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user']);
        $this->twoFactorAuthRepository = $twoFactorAuthRepository;
    }

    public function index(): Renderable
    {
        return view('users.settings.information');
    }

    public function indexSecurity(): Renderable
    {
        $google2faUrl = $this->twoFactorAuthRepository->getGoogle2FaUrl();
        return view('users.settings.security', compact('google2faUrl'));
    }

    public function updateInformation(InformationValidator $input): RedirectResponse
    {
        if ($this->getAuthenticatedUser()->update($input->all())) { // Update confirmation
            flash('Uw accunt informatie is met success aangepast.')->success()->important();
        }

        return redirect()->back(); // HTTP 302 - Redirect
    }

    public function updateSecurity(SecurityValidator $request): RedirectResponse
    {
        if ($this->getAuthenticatedUser()->update(['password' => $request->wachtwoord])) {
            auth()->logoutOtherDevices($request->huiding_wachtwoord);
            flash('Uw account beveiliging is met success aangepast.')->success()->important();
        }

        return redirect()->back();
    }
}
