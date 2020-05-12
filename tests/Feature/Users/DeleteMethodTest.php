<?php

namespace Tests\Feature\Users;

use App\Enums\UserRoles;
use App\Http\Controllers\Users\IndexController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\Concerns\CanAssertFlash;
use Tests\TestCase;

/**
 * Class DeleteMethodTest
 *
 * @package Tests\Feature\Users
 */
class DeleteMethodTest extends TestCase
{
    use RefreshDatabase;
    use CanAssertFlash;

    public function testMiddlewareInplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'destroy', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testFormValidationImplementation(): void
    {
        $this->markTestSkipped('TODO: Implement the test but we first need a refactor to a form request in the controller.');
    }

    public function testCanDisplayTheConfirmationView(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER]);

        $william = factory(User::class)->create()->assignRole($role);
        $helena = factory(User::class)->create();

        $this->actingAs($william)
            ->get(route('users.destroy', $helena))
            ->assertStatus(200)
            ->assertViewIs('users.delete');
    }

    public function testCanDeleteAnUser(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER]);

        $william = factory(User::class)->create(['password' => $password = 'rootTest'])->assignRole($role->name);
        $helena = factory(User::class)->create();

        $this->actingAs($william)
            ->delete(route('users.destroy', $helena), ['wachtwoord' => $password])
            ->assertstatus(302);

        $this->assertFlash('success', "De gebruiker {$helena->name} is verwijderd in de applicatie.", true);
        $this->assertDatabaseMissing('users', ['id' => $helena->id]);
    }
}
