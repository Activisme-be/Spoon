<?php

namespace Tests\Feature\Users;

use App\Enums\UserRoles;
use App\Http\Controllers\Users\IndexController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Class ShowMethodTest
 *
 * @package Tests\Feature\Users
 */
class ShowMethodTest extends TestCase
{
    use RefreshDatabase;

    private function generateUserRoles(): void
    {
        $roles = [UserRoles::ADMIN, UserRoles::USER, UserRoles::WEBMASTER];

        foreach ($roles as $key => $role) {
            factory(Role::class)->create(['name' => $role]);
        }
    }

    public function testMiddlewareImplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'show', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testResponseInvalidAuthorization(): void
    {
        $this->generateUserRoles();
        $kathy = factory(User::class)->create()->assignRole(['name' => UserRoles::USER]);

        $this->actingAs($kathy)
            ->get(route('users.show', $kathy))
            ->assertStatus(403);
    }

    public function testUserShowWithInvalidId(): void
    {
        $this->generateUserRoles();
        $kathy = factory(User::class)->create()->assignRole(['name' => UserRoles::WEBMASTER]);

        $this->actingAs($kathy)
            ->get(route('users.show', ['user' =>100000]))
            ->assertStatus(404);
    }

    public function testUserShowWithValidId(): void
    {
        $this->generateUserRoles();
        $kathy = factory(User::class)->create()->assignRole(['name' => UserRoles::WEBMASTER]);

        $this->actingAs($kathy)
            ->get(route('users.show', $kathy))
            ->assertStatus(200)
            ->assertViewIs('users.show');
    }
}
