<?php

namespace App\Domain\Auth\Http\Middleware;

use App\Models\User;
use Closure;

/**
 * Class PortalMiddleware
 *
 * @package App\Http\Middleware
 */
class PortalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $portalName The name for the portal that is active.
     * @return mixed
     */
    public function handle($request, Closure $next, string $portalName)
    {
        if ($this->authenticatedUser()->on_kiosk && $portalName === 'application') {
            $this->authenticatedUser()->update(['on_kiosk' => false]);
        }

        if (! $this->authenticatedUser()->on_kiosk && $portalName === 'kiosk') {
            $this->authenticatedUser()->update(['on_kiosk' => true]);
        }

        return $next($request);
    }

    /**
     * Method for getting the authenticated user.
     *
     * @return User
     */
    private function authenticatedUser(): User
    {
        return auth()->user();
    }
}
