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
 * Class DeleteMethodTest
 *
 * @package Tests\Feature\Users
 */
class DeleteMethodTest extends TestCase
{
    use RefreshDatabase;

    public function testMiddlewareInplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'destroy', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
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
}
