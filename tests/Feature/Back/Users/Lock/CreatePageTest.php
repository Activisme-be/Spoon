<?php

namespace Tests\Feature\Back\Users\Lock;

use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CreatePageTest
 *
 * @package Tests\Feature\Back\Users\Lock
 */
class CreatePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test that the currently authenticated can't deactivate himself.
     */
    public function sameUser(): void
    {
        $user = $this->createUser('admin');
        $this->actingAs($user)->get(route('users.lock', $user))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @testdox Test that a user with incorrect permissions can't block an user.
     */
    public function wrongPermission(): void
    {
        $user   = $this->createUser('user');
        $admin  = $this->createUser('admin');

        $this->actingAs($user)->get(route('users.lock', $admin))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @test Test that the method won't work on a user that is already blocked.
     */
    public function deactivatedUser(): void
    {
        $admin = $this->createUser('admin');
        $deactivatedUser = $this->createUserBlocked();

        $this->actingAs($admin)->get(route('users.lock', $deactivatedUser))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @testdox Test that the user can be banned without any errors.
     */
    public function success(): void
    {
        $webmaster = $this->createUser('webmaster');
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->get(route('users.lock', $webmaster))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @testdox test that an unauthenticated can't deactivate a user
     */
    public function unauthenticated(): void
    {
        $user = $this->createUser('user');
        $this->get(route('users.lock', $user))->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }
}
