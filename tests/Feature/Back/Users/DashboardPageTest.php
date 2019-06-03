<?php

namespace Tests\Feature\Back\Users;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DashboardPageTest
 *
 * @package Tests\Feature\Back\Users
 */
class DashboardPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test that an quest user can't access the user management dashboard
     */
    public function unauthenticated(): void
    {
        $this->get(route('users.index'))->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }

    /**
     * @test
     * @testdox Test That an user with wrong permissions can't access the user management dashboard
     */
    public function wrongPermission(): void
    {
        $user = $this->createUser('user');
        $this->actingAs($user)->get(route('users.index'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @testdox Test that an deactivated admin can't access the user management dashboard
     */
    public function deactivatedUser(): void
    {
        $user = $this->createUserBlocked();

        $this->actingAs($user)->get(route('users.index'))
            ->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('user.blocked'));
    }

    /**
     * @test
     * @testdox Test that an administrator can access the user management panel without problems.
     */
    public function success(): void
    {
        $user = $this->createUser('admin');
        $this->actingAs($user)->get(route('users.index'))->assertStatus(Response::HTTP_OK);
    }
}
