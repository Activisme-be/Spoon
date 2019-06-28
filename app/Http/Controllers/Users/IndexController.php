<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\InformationValidator;
use App\Notifications\LoginCreated;
use App\Models\User;
use Gate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $input->merge(['password' => Str::random(16)]);

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

    /**
     * Method for deleting the account in the application.
     *
     * @throws \Exception When we can't perform the user delete.
     *
     * @param  Request $request The request entity that holds all the request information.
     * @param  User    $user    The database entity from the given user.
     * @return View|RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        // 1) Request type is GET. So we need to display the confirmation view.
        // 2) Determine whether the user is deleted or not.
        // 3) Determine that the action needs to be logged or not.
        
        if ($request->isMethod('GET')) { // (1)
            return view('users.delete', compact('user'));
        }

        $request->validate(['wachtwoord' => 'required', 'string']);

        if ($user->securedRequest($request->wachtwoord) && $user->delete()) { // (2)
            if (Gate::denies('same-user')) { // (3)
                auth()->user()->logActivity($user, 'Gebruikers', "Heeft de gebruiker {$user->name} verwijderd in de applicatie.");
            }

            flash("De gebruiker {$user->name} is verwijderd in de applicatie.")->success()->important();
            return redirect()->route('users.index');
        }

        flash("Wij konden de gebruiker {$user->name} niet verwijderen in de applicatie.")->error()->important();
        return redirect()->route('users.destroy', $user);
    }
}
