<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Http\Response; 
use App\Http\Requests\Users\LockValidator;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Method for deactivating users in the application. 
     *
     * @param  LockValidator $input         The form request class that handles the validation. 
     * @param  User          $userEntity    The database entity form the given user
     * @return RedirectResponse 
     */
    public function store(LockValidator $input, User $userEntity): RedirectResponse 
    {
        $this->authorize('deactivate-user', $userEntity); 

        if ($userEntity->isNotBanned() && auth()->user()->securedRequest($input->wachtwoord)) {
            $userEntity->ban(['comment' => $input->reden]);
            auth()->user()->logActivity($userEntity, 'Gebruikers', "Heeft de login van {$userEntity->name} gedeactiveerd in het systeem.");
        }

        return redirect()->route('users.show', $userEntity);
    }
}
