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
    /**
     * @var AuthenticatorRepository
     */
    private $authenticator;

    /**
     * TwoFactorResetController constructor.
     *
     * @param  AuthenticatorRepository $authenticator
     * @return void
     */
    public function __construct(AuthenticatorRepository $authenticator)
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('handle');

        $this->authenticator = $authenticator;
    }

    /**
     * Method for displaying the 2fa recovery view.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        if ($this->authenticator->canDisplayRecoveryView()) {
            return view('auth.2fa-recovery');
        }

        // Currently wa can't display the reset form for the 2FA system.
        // Because the module is not enabled of the user is currently authenticated.
        abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Method for requesting a reqet of the 2FA authentication for the user.
     *
     * @param  TwoFactorRecoveryRequest $request The request class that handles all the validation logic.
     * @return RedirectResponse
     */
    public function request(TwoFactorRecoveryRequest $request): RedirectResponse
    {
        // Password hash check is performed on the Form validation.
        // If the user password is not the same than te given password this logic will not be executed.

        $user = $this->getAuthenticatedUser();

        if ($this->authenticator->canDisplayRecoveryView()) {
            DB::transaction(static function () use ($request, $user): void {
                $user->passwordSecurity()->update(['reset_requested' => true]);
                $user->notify(new TwoFactorResetNotification());
            });
        }

        session()->flash('status', 'We hebben je reset aanvraag goed ontvangen. Binnen enkele ogenblikken ontvang je een reset mail van ons.');
        return redirect()->back();
    }

    /**
     * Handle the reset method for the 2FA system.
     *
     * @return RedirectResponse
     */
    public function handle(): RedirectResponse
    {
        abort_if(! $this->authenticator->canDisplayRecoveryView(), Response::HTTP_NOT_FOUND);
        $user = $this->getAuthenticatedUser();

        try {
            if ($user->passwordSecurity()->exists() && $user->passwordSecurity->reset_requested) {
                $user->passwordSecurity()->delete();

                auth()->logout();
                session()->get('status', 'Het 2FA authenticatie systeem is verwijderd van jouw account.');
            }
        } catch (InvalidSignatureException $exception) {
            session()->flash('status', 'Wij konden momenteel de 2FA van het gegeven account niet resetten');
            return redirect()->route('recovery.2fa');
        }

        return redirect()->route('welcome');
    }
}
