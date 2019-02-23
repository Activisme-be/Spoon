<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class IndexController
 * 
 * @package App\Http\Controllers\Users
 */
class IndexController extends Controller
{
    /**
     * Create new IndexController constructor 
     * 
     * @return void
     */
    public function __construct() 
    {
        $this->middleware(['auth']);
    }

    /**
     * Method to display all the users in the application. 
     * 
     * @param  Request      $request The request instance that holds all the information about the request.
     * @param  User         $users   The database model builder for the application users
     * @return Renderable 
     */
    public function index(Request $request, User $users): Renderable 
    {
        switch ($request->filter) {
            case 'actief':        $users = $users->withoutBanned(); break;
            case 'gedeactiveerd': $users = $users->onlyBanned();    break;
        }

        return view('users.index', ['users' => $users->paginate()]);
    }

    /**
     * Method for displaying the create view for an new user. 
     *
     * @return Renderable
     */
    public function create(): Renderable 
    {
        return view('users.create');
    }
}
