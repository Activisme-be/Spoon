<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Class RegisterControllerTest
 *
 * @package Tests\Feature\Http\Controllers\Auth
 */
class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function successfulRegistrationRoute(): string
    {
        return route('home');
    }

    protected function registerGetRoute(): string
    {
        return route('register');
    }

    protected function registerPostRoute(): string
    {
        return route('register');
    }

    protected function guestMiddlewareRoute(): string
    {
        return route('home');
    }

    public function testUserCanViewARegistrationForm(): void
    {
        $response = $this->get($this->registerGetRoute());
        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function testUserCannotViewARegistrationFormWhenAuthenticated(): void
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->registerGetRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCanRegister(): void
    {
        Event::fake();

        $response = $this->post($this->registerPostRoute(), [
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $response->assertRedirect($this->successfulRegistrationRoute());

        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);

        $this->assertTrue(Hash::check('i-love-laravel', $user->password));

        Event::assertDispatched(Registered::class, function (Registered $event) use ($user): bool {
            return $event->user->id === $user->id;
        });
    }

    public function testUserCannotRegisterWithoutName(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'voornaam' => '',
            'achternaam' => '',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['voornaam', 'achternaam']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutEmail(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithInvalidEmail(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPassword(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPasswordConfirmation(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => '',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithPasswordsNotMatching(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-symfony',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
