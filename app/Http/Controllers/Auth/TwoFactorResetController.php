<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\TwoFactorRecoveryRequest;
use App\Notifications\TwoFactorResetNotification;
use App\Repositories\TwoFactorAuth\Authenticator as AuthenticatorRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\DB;

/**
 * Class TwoFactorResetController
 *
 * @package App\Http\Controllers\Auth
 */
class TwoFactorResetController extends Controller
{
    private AuthenticatorRepository $authenticator;

    public function __construct(AuthenticatorRepository $authenticator)
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('handle');

        $this->authenticator = $authenticator;
    }

    public function index(): Renderable
    {
        if ($this->authenticator->canDisplayRecoveryView()) {
            return view('auth.2fa-recovery');
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function request(TwoFactorRecoveryRequest $request): RedirectResponse
    {
        $user = $this->getAuthenticatedUser();

        if ($this->authenticator->canDisplayRecoveryView()) {
            DB::transaction(static function () use ($user): void {
                dd($user->twoFactorAuthentication->google2fa_recovery_tokens);
            });
        }

        return redirect()->back();
    }
}
