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
            default: return view('users.settings.information');
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
}
