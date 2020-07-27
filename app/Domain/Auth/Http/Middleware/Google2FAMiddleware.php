<?php

namespace App\Domain\Auth\Http\Middleware;

use App\Repositories\TwoFactorAuth\Authenticator;
use Closure;

/**
 * Class Google2FAMiddleware
 *
 * @package App\Http\Middleware
 */
class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }

        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
