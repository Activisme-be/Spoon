<?php

namespace Tests\Feature\Front;

use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class WelcomePageTest
 *
 * @package Tests\Feature\Front
 */
class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test that the welcome page from the application can be viewed as guest.
     */
    public function WelcomePageAsGuest(): void
    {
        $this->get(route('welcome'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @testdox Test the redirect to the admin dashboaard if the user is already logged in.
     */
    public function WelcomePageAsLoggedInUser(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('welcome'))->assertRedirect(route('home'))->assertStatus(Response::HTTP_FOUND);
    }
}
