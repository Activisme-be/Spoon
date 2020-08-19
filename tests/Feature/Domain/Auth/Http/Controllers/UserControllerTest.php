<?php

namespace Tests\Feature\Domain\Auth\Http\Controllers;

use App\Domain\Auth\Enums\UserRoles;
use App\Domain\Auth\Http\Controllers\UserController;
use App\Domain\Auth\Http\Requests\InformationValidator;
use App\Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Tests\Concerns\CanAssertFlash;
use Tests\TestCase;

/**
 * Class UserControllerTest
 *
 * @package Tests\Feature\Domain\Auth\Http\Controllers
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanAssertFlash;

    private function createAdminuser(): User
    {
        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER])->name;

        return factory(User::class)
            ->create()
            ->assignRole($role);
    }

    private function requestData(): array
    {
        $roles = factory(Role::class, 2)->create();

        return [
            'voornaam' => $this->faker->firstName,
            'achternaam' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'roles' => [$roles[0], $roles[1]],
        ];
    }

    /**
     * @testdox Test if the middleware is implemented correctly on the UserController
     * @see \App\Domain\Auth\Http\Controllers\UserController::__construct()
     */
    public function testMiddlewareImplementation(): void
    {
        $middlewareStack = ['auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'];

        $this->assertActionUsesMiddleware(UserController::class, 'index', $middlewareStack);
        $this->assertActionUsesMiddleware(UserController::class, 'show', $middlewareStack);
        $this->assertActionUsesMiddleware(UserController::class, 'create', $middlewareStack);
        $this->assertActionUsesMiddleware(UserController::class, 'search', $middlewareStack);
        $this->assertActionUsesMiddleware(UserController::class, 'store', $middlewareStack);
        $this->assertActionUsesMiddleware(UserController::class, 'update', $middlewareStack);
        $this->assertActionUsesMiddleware(UserController::class, 'destroy', $middlewareStack);
    }

    /**
     * @testdox Test the overview page from the users in the application.
     */
    public function testIndexMethod(): void
    {
        factory(User::class, 30)->create();

        $william = $this->createAdminuser();

        $this->get(route('users.index'))->assertRedirect(route('login'));

        foreach (['actief', 'gedeactiveerd'] as $value => $filter) {
            $this->actingAs($william)
                ->get(route('users.index', ['filter' => $filter]))
                ->assertStatus(Response::HTTP_OK)
                ->assertViewIs('users.index');
        }

        $this->actingAs($william)
            ->get(route('users.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('users.index');
    }

    /**
     * @textdox Test the search functionality for the user overview.
     */
    public function testSearchMethod(): void
    {
        $william = $this->createAdminuser();
        $searchTerm = ['term' => $william->name];

        $this->get(route('users.search', $searchTerm))->assertRedirect(route('login'));

        $this->actingAs($william)
            ->get(route('users.search', $searchTerm))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('users.index');
    }

    /**
     * @testdox Test if the an admin or webmaster can view the user creation view successfully.
     */
    public function testCreateMethod(): void
    {
        $william = $this->createAdminuser();
        factory(Role::class, 30)->create();

        $this->get(route('users.create'))->assertRedirect(route('login'));

        $this->actingAs($william)
            ->get(route('users.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('users.create');
    }

    /**
     * @textdox Test uf the authentica  ted user can successfully view the given user.
     */
    public function testShowMethod(): void
    {
        $william = $this->createAdminuser();
        factory(Role::class, 30)->create();

        $this->get(route('users.show', $william))->assertRedirect(route('login'));

        $this->actingAs($william)
            ->get(route('users.show', $william))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('users.show');
    }

    /**
     * @testdox Test if an user in the application can updfate the information from other users.
     */
    public function testUpdateMethod(): void
    {
        $william = $this->createAdminuser();
        $helena = factory(User::class)->create();
        $requestData = $this->requestData();

        $this->assertActionUsesFormRequest(UserController::class, 'update', InformationValidator::class);

        $this->patch(route('users.update', $helena), $requestData)
            ->assertRedirect(route('login'));

        $this->actingAs($william)
            ->patch(route('users.update', $helena), $requestData)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertFlash('success', "De gegevens van {$requestData['voornaam']} {$requestData['achternaam']} zijn aangepast in de applicatie");
    }
}
