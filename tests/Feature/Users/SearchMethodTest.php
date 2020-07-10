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
 * Class SearchMethodTest
 *
 * @package Tests\Feature\Users
 */
class SearchMethodTest extends TestCase
{
    use RefreshDatabase;

    public function testMiddlewareImplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'search', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testUnauthorizedAccess(): void
    {
        $role    = factory(Role::class)->create(['name' => UserRoles::USER]);
        $william = factory(User::class)->create()->assignRole($role->name);

        factory(User::class, '20')->create();

        $this->actingAs($william)
            ->get(route('users.search'), ['term' => 'search term'])
            ->assertStatus(403);
    }

    public function testUserCanSearchWithOutAnyErrors(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::ADMIN]);
        $william = factory(User::class)->create()->assignRole($role->name);

        $users = factory(User::class, 30)->create();

        $this->actingAs($william)
            ->get(route('users.search', ['term' => $users[0]->email]))
            ->assertStatus(200)
            ->assertViewIs('users.index');
    }
}
