<?php

namespace Tests\Feature\Back\Activity;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class UserEndpointTest
 *
 * @package Tests\Feature\Back\Actitivy
 */
class UserEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test that an quest user can't access the user activity page for an user.
     */
    public function notAuthenticated(): void
    {
        $user = factory(User::class)->create();
        $this->get(route('users.activity', $user))->assertRedirect(route('login'))->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @test
     * @testdox Test that an authenticated user with correct permissions can view the page.
     */
    public function success(): void
    {
        $user = $this->createUser('admin');
        $this->actingAs($user)->get(route('users.activity', $user))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @testdox Test that an user with incorrect permissions can't access the activity page.
     */
    public function incorrectRole(): void
    {
        $user = $this->createUser('user');
        $this->actingAs($user)->get(route('users.activity', $user))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @testdox Test that an deactivated user can't access the activity page.
     */
    public function deactivatedUser(): void
    {
        $user = $this->createUserBlocked();

        $this->actingAs($user)->get(route('users.activity', $user))->assertRedirect(route('user.blocked'))
            ->assertStatus(Response::HTTP_FOUND);
    }
}
