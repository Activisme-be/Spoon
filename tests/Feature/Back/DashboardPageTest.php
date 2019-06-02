<?php

namespace Tests\Feature\Back;

use App\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DashboardPageTest
 *
 * @package Tests\Feature\Back
 */
class DashboardPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test that a guest user will be redirected to the login page when trying to access the dashboard page.
     */
    public function notAuthtenticated(): void
    {
        $this->get(route('home'))->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }

    /**
     * @test
     * @testdox Test that an authenticated user with the right permissions can view the dashboard without problems.
     */
    public function authenticatedUserSuccess(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('home'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @testdox Test that a blocked user with right permissions can't access the dashboard page.
     */
    public function redirectBlockedUser(): void
    {
        $user  = factory(User::class)->create()->ban();
        $authUser = User::find($user->id);

        $this->actingAs($authUser)
            ->get(route('home'))
            ->assertRedirect(route('user.blocked'))
            ->assertStatus(Response::HTTP_FOUND);
    }
}
