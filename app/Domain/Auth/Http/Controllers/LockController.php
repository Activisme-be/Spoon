<?php

namespace App\Domain\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Auth\Http\Requests\LockValidator;
use App\SDomain\Auth\Models\User;
use App\Domain\Auth\Notifications\LockNotification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Class LockController
 *
 * @package App\Http\Controllers\Users
 */
class LockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'portal:kiosk'])->except(['index']);
    }

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(LockValidator $input, User $userEntity): RedirectResponse
    {
        $this->authorize('deactivate-user', $userEntity);

        DB::transaction(function () use ($input, $userEntity): void {
            $userEntity->ban(['comment' => $input->reden]);
            $input->user()->notify(new LockNotification($input->user()->name));

            $this->getAuthenticatedUser()->logActivity($userEntity, 'Gebruikers', "Heeft de login van {$userEntity->name} gedeactiveerd in het systeem.");
        });

        return redirect()->route('users.show', $userEntity);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
