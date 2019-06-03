<?php

namespace Tests\Feature\Back\Users\Lock;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ErrorPageTest
 *
 * @package Tests\Feature\Back\Users\Lock
 */
class ErrorPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Test the redirect if a quest user tries to access the page.
     */
    public function unauthenticated(): void
    {
        $this->get(route('user.blocked'))->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }

    /**
     * @test
     * @testdox Test the response when a activated user tries to access the error page.
     */
    public function notDeactivatedUser(): void
    {
        $user = $this->createUser('user');
        $this->actingAs($user)->get('user.blocked')->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     * @testdox Test that an deactivated user can view the error page without any errors.
     */
    public function deactivatedUser(): void
    {
        $user = $this->createUserBlocked();
        $this->actingAs($user)->get(route('user.blocked'))->assertStatus(Response::HTTP_OK);
    }
}
