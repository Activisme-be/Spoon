<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class LoginTest
 *
 * @package Tests\Feature\Auth
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function successfulLoginRoute(): string
    {
        return route('home');
    }

    protected function loginGetRoute(): string
    {
        return route('login');
    }

    protected function loginPostRoute(): string
    {
        return route('login');
    }

    protected function logoutRoute(): string
    {
        return route('logout');
    }

    protected function successfulLogoutRoute(): string
    {
        return '/';
    }

    protected function guestMiddlewareRoute(): string
    {
        return route('home');
    }

    protected function getTooManyLoginAttemptsMessage(): string
    {
        return sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/')));
    }

    public function testUserCanViewALoginForm()
    {
        $response = $this->get($this->loginGetRoute());
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function testUserCannotViewALoginFormWhenAuthenticated(): void
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->loginGetRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCanLoginWithCorrectCredentials(): void
    {
        $user = factory(User::class)->create([
            'password' => $password = 'i-love-laravel',
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'email' => $user->email, 'password' => $password,
        ]);

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);
    }

    public function testRememberMeFunctionality(): void
    {
        $user = factory(User::class)->create([
            'id' => random_int(1, 100), 'password' => $password = 'i-love-laravel',
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'email' => $user->email, 'password' => $password, 'remember' => 'on',
        ]);

        $user = $user->fresh();

        $response->assertRedirect($this->successfulLoginRoute());
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id, $user->getRememberToken(), $user->password
        ]));

        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginWithIncorrectPassword(): void
    {
        $user = factory(User::class)->create(['password' => 'i-love-laravel']);

        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
            'email' => $user->email, 'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotLoginWithEmailThatDoesNotExist(): void
    {
        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
            'email' => 'nobody@example.com', 'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCanLogout(): void
    {
        $this->be(factory(User::class)->create());

        $response = $this->post($this->logoutRoute());
        $response->assertRedirect($this->successfulLogoutRoute());

        $this->assertGuest();
    }

    public function testUserCannotLogoutWhenNotAuthenticated(): void
    {
        $response = $this->post($this->logoutRoute());
        $response->assertRedirect($this->successfulLogoutRoute());

        $this->assertGuest();
    }

    public function testUserCannotMakeMoreThanFiveAttemptsInOneMinute(): void
    {
        $user = factory(User::class)->create(['password' => $password = 'i-love-laravel']);

        foreach (range(0, 5) as $_) {
            $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
                'email' => $user->email, 'password' => 'invalid-password',
            ]);
        }

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertRegExp(
            $this->getTooManyLoginAttemptsMessage(),
            collect($response->baseResponse->getSession()->get('errors')->getBag('default')->get('email'))->first()
        );

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
