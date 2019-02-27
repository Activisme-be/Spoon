<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Users\InformationValidator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class AccountController
 * 
 * @package App\Http\Controllers\Users
 */
class AccountController extends Controller
{
    /**
     * AccountConstroller constructor 
     * 
     * @return void 
     */
    public function __construct() 
    {
        $this->middleware(['auth', 'forbid-banned-user']);
    }

    /**
     * Method for displaying the account seetings vie. 
     * 
     * @param  Request $request The instance that holds all the request information.
     * @return Renderable 
     */
    public function index(Request $request): Renderable 
    {
        // Determine on the switch statement which vierw the user wants to display
        // for modifying this account settings 
        switch ($request->type) {
            case 'beveiliging': return view('users.settings.security');
            default:            return view('users.settings.information');
        }
    }

    /**
     * Method for updating the account information from the authenticated user. 
     * 
     * @param  InformationValidator $input The form request class that handles the validation.
     * @return RedirectResponse 
     */
    public function updateInformation(InformationValidator $input): RedirectResponse 
    {
        if (auth()->user()->update($input->all())) { // Update confirmation 
            flash('Uw accunt informatie is met success aangepast.')->success()->important();
        }

        return redirect()->back(); // HTTP 302 - Redirect
    }

    /**
     * Method for update the account security from the authenticated used.
     *
     * @param  Request $request The instance that holds all the request information.
     * @param  User    $user    The database model for the login in the application
     * @return RedirectResponse
     */
    public function updateSecurity(Request $request, User $user): RedirectResponse
    {
        $request->validate(['wachtwoord' => ['required', 'string', 'min:6', 'confirmed'], 'huidig_wachtwoord' => ['required', 'string']]);

        // User can only update his account security when the old password is correct.
        if (auth()->user()->securedRequest($request->huidig_wachtwoord)) {
            auth()->logoutOtherDevices($request->huiding_wachtwoord);

            // The password has been updated successfully.
            if (auth()->user()->update(['password' => $request->wachtwoord])) {
                flash('Uw account beveiliging is met success aangepast.')->success()->important();
                return redirect()->back();
            }
        }
        
        flash('Uw huidige wachtwoord komt niet overeen met het gegeven oude wachtwoord!')->error()->important();
        return redirect()->back();
    }
}
