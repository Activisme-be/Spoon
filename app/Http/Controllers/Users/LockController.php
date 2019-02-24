<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class LockController 
 * 
 * @package App\Http\Controllers
 */
class LockController extends Controller
{
    /**
     * LockController constructor 
     * 
     * @return void 
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Method for displaying the confirmation view for blocking a user. 
     * 
     * @param  User $userEntity  The database entity from the given user. 
     * @return Renderable
     */
    public function create(User $userEntity): Renderable 
    {
        if ($userEntity->isNotBanned() && auth()->user()->can('deactivate-user', $userEntity)) {
            return view('users.lock', compact('userEntity'));
        } 

        // The user is not banned or the authenticated user is the same user than the given user. 
        // So there is no need to display the lock page. 
        abort(Response::HTTP_FORBIDDEN);
    }
}
