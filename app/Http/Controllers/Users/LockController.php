<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LockValidator;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Class LockController.
 */
class LockController extends Controller
{
    /**
     * LockController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'portal:kiosk'])->except(['index']);
    }

    /**
     * Method for displaying the deactivated user error page.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $user = $this->getAuthenticatedUser();

        // Check if the user is actually banned in the application.
        if ($user->isBanned()) {
            $banInfo = $user->bans()->latest()->first();
            return view('errors.deactivated', compact('banInfo'));
        }

        // We can't the lock on the user so there is no page to be displayed.
        // So redirect the user back to the dashboard page.
        return abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Method for displaying the confirmation view for blocking a user.
     *
     * @param  User $userEntity  The database entity from the given user.
     * @return Renderable
     */
    public function create(User $userEntity): Renderable
    {
        if ($this->getAuthenticatedUser()->can('deactivate-user', $userEntity)) {
            return view('users.lock', compact('userEntity'));
        }

        // The user is not banned or the authenticated user is the same user than the given user.
        // So there is no need to display the lock page.
        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Method for deactivating users in the application.
     *
     * @param  LockValidator $input         The form request class that handles the validation.
     * @param  User          $userEntity    The database entity from the given user
     * @return RedirectResponse
     */
    public function store(LockValidator $input, User $userEntity): RedirectResponse
    {
        $this->authorize('deactivate-user', $userEntity);

        $userEntity->ban(['comment' => $input->reden]);
        $this->getAuthenticatedUser()->logActivity($userEntity, 'Gebruikers', "Heeft de login van {$userEntity->name} gedeactiveerd in het systeem.");

        return redirect()->route('users.show', $userEntity);
    }

    /**
     * Method for activating users in the application.
     *
     * @param  User $userEntity The database entity from the given user.
     * @return RedirectResponse
     */
    public function destroy(User $userEntity): RedirectResponse
    {
        $this->authorize('activate-user', $userEntity);

        $userEntity->unban();
        $this->getAuthenticatedUser()->logActivity($userEntity, 'Gebruikers', "heeft de login van {$userEntity->name} terug geactiveerd in het systeem.");
        flash("De login van {$userEntity->name} is terug actief in het systeem.")->success();

        return redirect()->route('users.show', $userEntity);
    }
}
