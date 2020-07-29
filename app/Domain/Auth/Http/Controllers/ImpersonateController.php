<?php

namespace App\Domain\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Auth\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Lab404\Impersonate\Services\ImpersonateManager;
use Symfony\Component\HttpFoundation\Response;
use function abort_if;

/**
 * Class ImpersonateController
 *
 * @package App\Http\Controllers\Users
 */
class ImpersonateController extends Controller
{
    protected ImpersonateManager $impersonateManager;

    public function __construct()
    {
        $this->impersonateManager = app()->make(ImpersonateManager::class);
        $this->middleware(['auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk']);
    }

    public function take(Request $request, User $user, ?string $guardName = null): RedirectResponse
    {
        $this->authorize('impersonate', [$user, $guardName]);

        if ($user->canBeImpersonated() && $this->impersonateManager->take($request->user(), $user, $guardName)) {
            $takeRedirect = $this->impersonateManager->getTakeRedirectTo();
            $request->user()->logActivity($user, 'Gebruikers', 'Heeft een impersonatie sessie gestart voor ' . $user->name);

            if ($takeRedirect !== 'back') {
                return redirect()->to($takeRedirect);
            }
        }

        return redirect()->back();
    }

    public function leave(): RedirectResponse
    {
        $this->authorize('leave-impersonate', User::class);

        $this->impersonateManager->leave();
        $leaveRedirect = $this->impersonateManager->getLeaveRedirectTo();

        if ($leaveRedirect !== 'back') {
            return redirect()->to($leaveRedirect);
        }

        return redirect()->back();
    }
}
