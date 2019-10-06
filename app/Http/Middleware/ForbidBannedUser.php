<?php

namespace App\Http\Middleware;

use Closure;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class ForbidBannedUser.
 */
class ForbidBannedUser
{
    /**
     * The guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create new BorbidBannedUser instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();

        if ($user && $user instanceof BannableContract && $user->isBanned()) {
            return redirect()->route('user.blocked');
        }

        return $next($request);
    }
}
