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
}
