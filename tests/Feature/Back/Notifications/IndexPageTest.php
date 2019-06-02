<?php

namespace Tests\Feature\Back\Notifications;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IndexPageTest
 *
 * @package Tests\Feature\Back\Notifications
 */
class IndexPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test that the quest user is redirect to the login page.
     */
    public function notAuthenticated(): void
    {
        $route = route('notifications.index');
        $this->get($route)->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }

    /**
     * @test
     * @testdox Test that an authenticated user can access the unread notifications index page without problems.
     */
    public function authenticatedUserUnread(): void
    {
        $user = $this->createUser('admin');
        $route = route('notifications.index');

        $this->actingAs($user)->get($route)->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @testdox Test if the authenticated user can view all the notifications in the application.
     */
    public function authenticatedUserAll(): void
    {
        $user = $this->createUser('admin');
        $route = route('notifications.index', ['filter' => 'alle']);

        $this->actingAs($user)->get($route)->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @testdox Test that an deactiviated user can't access the notifications index page.
     */
    public function deactivatedUser(): void
    {
        $user = $this->createUserBlocked();
        $route = route('notifications.index');

        $this->actingAs($user)->get($route)->assertRedirect(route('user.blocked'))
            ->assertStatus(Response::HTTP_FOUND);
    }
}
