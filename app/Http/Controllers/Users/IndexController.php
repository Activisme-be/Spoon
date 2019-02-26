<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\Notifications\LoginCreated;
use App\Http\Requests\Users\InformationValidator;
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
        $this->middleware(['auth', 'role:admin', 'forbid-banned-user']);
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

        $requestType = $request->filter;
        return view('users.index', ['users' => $users->paginate(), 'requestType' => $requestType]);
    }

    /**
     * Method for displaying the user information in the application. 
     * 
     * @param  User $user The database entity for the given user. 
     * @return Renderable
     */
    public function show(User $user): Renderable 
    {
        $cantEdit = auth()->user()->cannot('can-edit', $user);
        return view('users.show', compact('user', 'cantEdit'));
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

    /**
     * Method for searching specific user account in the application. 
     * 
     * @param  Request $input THe request class that holds all the request information. 
     * @return Renderable
     */
    public function search(Request $request, User $users): Renderable 
    {
        return view('users.index', ['users' => $users->search($request->term)->paginate(), 'requestType' => 'search']);
    }

    /**
     * Method for storing the new user in the application. 
     * 
     * @param  InformationValidator $input The form request class that handles the input validation. 
     * @param  User                 $user  The database model entity class.
     * @return RedirectResponse
     */
    public function store(InformationValidator $input, User $user): RedirectResponse 
    {
        $input->merge(['password' => str_random(16)]);

        if ($user = $user->create($input->all())) {
            auth()->user()->logActivity($user, 'Gebruikers', "Heeft een login aangemaakt voor {$user->name}");
            $user->notify((new LoginCreated($input->all()))->delay(now()->addMinute())); // TODO: Implement notification class
        }

        return redirect()->route('users.show', $user);
    }

    /**
     * Method for updating the user in the application. 
     * 
     * @param  InformationValidator $input  The form request class that handles the validation. 
     * @param  User                 $user   The database entity form the given user. 
     * @return RedirectResponse
     */
    public function update(InformationValidator $input, User $user): RedirectResponse 
    {
        if (auth()->user()->can('can-edit', $user) &&  $user->update($input->all())) {
            flash("De gegevens van {$user->name} zijn aangepast in de applicatie")->success();
        }

        return redirect()->route('users.show', $user);
    }
}
