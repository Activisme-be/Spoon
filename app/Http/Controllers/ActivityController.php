<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class ActivityController
 * 
 * @package App\Http\Controllers
 */
class ActivityController extends Controller
{
    /**
     * Create new ActivityController instance. 
     * 
     * @return void
     */
    public function __construct() 
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Method to display the logged user operations in the application. 
     * 
     * @param  User $user   The database entity from the given user.
     * @return Renderable
     */
    public function show(User $user): Renderable
    {
        $activities = $user->actions()->orderBy('created_at', 'DESC')->simplePaginate(); 
        return view('activity.user', compact('activities', 'user'));
    }
}
