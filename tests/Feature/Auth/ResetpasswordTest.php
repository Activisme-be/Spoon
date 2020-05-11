<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ResetPasswordTest
 *
 * @package Tests\Feature\Auth
 */
class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function getValidToken($user): string
    {
        return Password::broker()->createToken($user);
    }

    protected function getInvalidToken(): string
    {
        return 'invalid-token';
    }

    protected function passwordResetGetRoute($token): string
    {
        return route('password.reset', $token);
    }

    protected function passwordResetPostRoute(): string
    {
        return '/password/reset';
    }

    protected function successfulPasswordResetRoute(): string
    {
        return route('home');
    }

    public function testUserCanViewAPasswordResetForm(): void
    {
        $user = factory(User::class)->create();

        $response = $this->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    public function testUserCanViewAPasswordResetFormWhenAuthenticated(): void
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    public function testUserCanResetPasswordWithValidToken(): void
    {
        Event::fake();
        $user = factory(User::class)->create();

        $response = $this->post($this->passwordResetPostRoute(), [
            'token' => $this->getValidToken($user),
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->successfulPasswordResetRoute());
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertAuthenticatedAs($user);

        Event::assertDispatched(PasswordReset::class, function ($e) use ($user): bool {
            return $e->user->id === $user->id;
        });
    }

    public function testUserCannotResetPasswordWithInvalidToken(): void
    {
        $user = factory(User::class)->create(['password' => 'old-password']);

        $response = $this->from($this->passwordResetGetRoute($this->getInvalidToken()))->post($this->passwordResetPostRoute(), [
            'token' => $this->getInvalidToken(),
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($this->getInvalidToken()));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    public function testUserCannotResetPasswordWithoutProvidingANewPassword(): void
    {
        $user = factory(User::class)->create(['password' => 'old-password']);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
            'token' => $token,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    public function testUserCannotResetPasswordWithoutProvidingAnEmail(): void
    {
        $user = factory(User::class)->create(['password' => 'old-password']);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
            'token' => $token,
            'email' => '',
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));
        $response->assertSessionHasErrors('email');

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
