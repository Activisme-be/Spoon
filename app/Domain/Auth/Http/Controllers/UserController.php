<?php

namespace App\Domain\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Auth\Http\Requests\InformationValidator;
use App\Models\User;
use App\Notifications\LoginCreated;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * Class IndexController
 *
 * @package App\Http\Controllers\Users
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk']);
    }

    public function index(Request $request, User $users): Renderable
    {
        switch ($request->filter) {
            case 'actief':        $users = $users->withoutBanned(); break;
            case 'gedeactiveerd': $users = $users->onlyBanned();    break;
        }

        $requestType = $request->filter;
        return view('users.index', ['users' => $users->paginate(), 'requestType' => $requestType]);
    }

    public function show(User $user): Renderable
    {
        $roles = Role::all(['name']);

        return view('users.show', compact('user', 'roles'));
    }

    public function create(Role $roles): Renderable
    {
        $roles = $roles->pluck('name', 'name')->toArray();
        return view('users.create', compact('roles'));
    }

    public function search(Request $request, User $users): Renderable
    {
        return view('users.index', ['users' => $users->search($request->term)->paginate(), 'requestType' => 'search']);
    }

    public function store(InformationValidator $input): RedirectResponse
    {
        $input->merge(['password' => Str::random(16)]);

        $user = DB::transaction(static function () use ($input): User {
            $user = User::create($input->all());
            $user->syncRoles($input->role);

            $user->notify((new LoginCreated($input->all()))->delay(now()->addMinute()));
            (new Controller())->getAuthenticatedUser()->logActivity($user, 'Gebruikers', "Heeft een login aangemaakt voor {$user->name}");

            return $user;
        });

        return redirect()->route('users.show', $user);
    }

    public function update(InformationValidator $input, User $user): RedirectResponse
    {
        if ($user->update($input->all())) {
            $user->syncRoles($input->roles);

            if ($user->isNot($this->getAuthenticatedUser())) {
                $this->getAuthenticatedUser()->logActivity($user, 'Gebruikers', "Heeft de account gegevens van {$user->name} gewijzigd.");
            }

            flash("De gegevens van {$user->name} zijn aangepast in de applicatie")->success();
        }

        return redirect()->route('users.show', $user);
    }

    public function destroy(Request $request, User $user)
    {
        // 1) Request type is GET. So we need to display the confirmation view.
        // 2) Determine whether the user is deleted or not.
        // 3) Determine that the action needs to be logged or not.

        if ($request->isMethod('GET')) { // (1)
            return view('users.delete', compact('user'));
        }

        $request->validate(['wachtwoord' => 'required', 'string']);

        if (Hash::check($request->wachtwoord, $this->getAuthenticatedUser()->getAuthPassword()) && $user->delete()) { // (2)
            if (Gate::denies('same-user')) { // (3)
                $this->getAuthenticatedUser()->logActivity($user, 'Gebruikers', "Heeft de gebruiker {$user->name} verwijderd in de applicatie.");
            }

            flash("De gebruiker {$user->name} is verwijderd in de applicatie.")->success()->important();
            return redirect()->route('users.index');
        }

        flash("Wij konden de gebruiker {$user->name} niet verwijderen in de applicatie.")->error()->important();
        return redirect()->route('users.destroy', $user);
    }
}
