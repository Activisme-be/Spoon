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
 * Class IndexMethodTest
 *
 * @package Tests\Feature\Users
 */
class IndexMethodTest extends TestCase
{
    use RefreshDatabase;

    public function testMiddlewareImplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'index', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testAuthorizedResponse(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::USER]);
        $william = factory(User::class)->create()->assignRole($role->name);

        $this->actingAs($william)
            ->get(route('users.index'))
            ->assertStatus(403);
    }

    public function testRequestIsSuccessfullWithNoFilter(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER]);
        $william = factory(User::class)->create()->assignRole($role->name);

        $this->actingAs($william)
            ->get(route('users.index'))
            ->assertStatus(200)
            ->assertViewIs('users.index');
    }

    public function testRequestIsSuccessfullWithFilter(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER]);
        $william = factory(User::class)->create()->assignRole($role->name);

        $this->actingAs($william)
            ->get(route('users.index', ['filter' => 'actief']))
            ->assertStatus(200)
            ->assertViewIs('users.index');
    }
}
