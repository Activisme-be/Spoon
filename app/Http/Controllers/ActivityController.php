<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $this->middleware(['auth', 'role:admin', 'forbid-banned-user']);
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
