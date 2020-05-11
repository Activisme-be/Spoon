<?php

namespace Tests\Feature\Users;

use App\Http\Controllers\Users\IndexController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function testGetMethodForDeletingUsers(): void
    {
        $william = factory(User::class)->create();
    }
}
