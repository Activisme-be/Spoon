<?php

namespace Tests\Feature\Back\Users;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CreatePageTest
 *
 * @package Tests\Feature\Back\Users
 */
class CreatePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test if an authenticated user can't access the user create view.
     */
    public function unauthenticated(): void
    {
        $this->get(route('users.create'))->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }

    /**
     * @test
     * @testdox Test if an deactivated user can't acess the user create view.
     */
    public function deactivatedUser(): void
    {
        $user = $this->createUserBlocked();

        $this->actingAs($user)->get(route('users.create'))
            ->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('user.blocked'));
    }

    /**
     * @test
     * @testdox Test that a user with wrong permissions can't access the user create view.
     */
    public function wrongPermissions(): void
    {
        $user = $this->createUser('user');
        $this->actingAs($user)->get(route('users.create'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @testdox Test that an user with the right permissions can view the page without any problems.
     */
    public function success(): void
    {
        $user = $this->createUser('admin');
        $this->actingAs($user)->get(route('users.create'))->assertStatus(Response::HTTP_OK);
    }
}
