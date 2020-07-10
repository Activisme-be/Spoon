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
 * Class CreateMethodTest
 *
 * @package Tests\Feature\Users
 */
class CreateMethodTest extends TestCase
{
    use RefreshDatabase;

    private function generateRoles()
    {
        $roles = [UserRoles::USER, UserRoles::WEBMASTER, UserRoles::ADMIN];

        foreach ($roles as $key => $role) {
            factory(Role::class)->create(['name' => $role]);
        }
    }

    public function testMiddlewareImplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'create', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testUnauthorizedAccessResponse(): void
    {
        $this->generateRoles();
        $stan = factory(User::class)->create()->assignRole(['name' => UserRoles::USER]);

        $this->actingAs($stan)
            ->get(route('users.create'))
            ->assertStatus(403);
    }

    public function testCanDisplayCreateViewSuccessFull(): void
    {
        $this->generateRoles();
        $stan = factory(User::class)->create()->assignRole(UserRoles::WEBMASTER);

        $this->actingAs($stan)
            ->get(route('users.create'))
            ->assertStatus(200)
            ->assertViewIs('users.create');
    }
}
