<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\Notifications\LoginCreated;
use App\Http\Requests\Users\CreateValidator;
use Illuminate\Support\Facades\Password; 
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
        $this->middleware(['auth', 'role:admin']);
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
     * @todo Create view
     *
     * @return Renderable
     */
    public function create(): Renderable 
    {
        return view('users.create');
    }

    /**
     * Method for storing the new user in the application. 
     * 
     * @param  CreateValidator $input The form request class that handles the input validation. 
     * @param  User            $user  The database model entity class.
     * @return RedirectResponse
     */
    public function store(CreateValidator $input, User $user): RedirectResponse 
    {
        $input->merge(['password' => str_random(16)]);

        if ($user = $user->create($input->all())) {
            auth()->user()->logActivity($user, 'Gebruikers', "heeft een login aangemaakt voor {$user->name}");
            $user->notify((new LoginCreated($input->all()))->delay(now()->addMinute())); // TODO: Implement notification class
        }

        return redirect()->route('users.index');
    }
}
