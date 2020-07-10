<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class EmailVerificationTest
 *
 * @package Tests\Feature\Auth
 */
class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected string $verificationVerifyRouteName = 'verification.verify';

    protected function successfulVerificationRoute(): string
    {
        return route('home');
    }

    protected function verificationNoticeRoute(): string
    {
        return route('verification.notice');
    }

    protected function validVerificationVerifyRoute($user): string
    {
        return URL::signedRoute($this->verificationVerifyRouteName, [
            'id' => $user->id, 'hash' => sha1($user->getEmailForVerification()),
        ]);
    }

    protected function invalidVerificationVerifyRoute($user): string
    {
        return route($this->verificationVerifyRouteName, [
            'id' => $user->id, 'hash' => 'invalid-hash'
        ]);
    }

    protected function verificationResendRoute(): string
    {
        return route('verification.resend');
    }

    protected function loginRoute(): string
    {
        return route('login');
    }

    public function testGuestCannotSeeTheVerificationNotice(): void
    {
        $response = $this->get($this->verificationNoticeRoute());
        $response->assertRedirect($this->loginRoute());
    }

    public function testUserSeesTheVerificationNoticeWhenNotVerified(): void
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get($this->verificationNoticeRoute());
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    public function testVerifiedUserIsRedirectedHomeWhenVisitingVerificationNoticeRoute(): void
    {
        $user = factory(User::class)->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get($this->verificationNoticeRoute());
        $response->assertRedirect($this->successfulVerificationRoute());
    }

    public function testGuestCannotSeeTheVerificationVerifyRoute(): void
    {
        $user = factory(User::class)->create(['id' => 1, 'email_verified_at' => null]);

        $response = $this->get($this->validVerificationVerifyRoute($user));
        $response->assertRedirect($this->loginRoute());
    }

    public function testUserCannotVerifyOthers(): void
    {
        $user = factory(User::class)->create(['id' => 1, 'email_verified_at' => null]);
        $user2 = factory(User::class)->create(['id' => 2, 'email_verified_at' => null]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user2));
        $response->assertForbidden();

        $this->assertFalse($user2->fresh()->hasVerifiedEmail());
    }

    public function testUserIsRedirectedToCorrectRouteWhenAlreadyVerified(): void
    {
        $user = factory(User::class)->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user));
        $response->assertRedirect($this->successfulVerificationRoute());
    }

    public function testForbiddenIsReturnedWhenSignatureIsInvalidInVerificationVerifyRoute(): void
    {
        $user = factory(User::class)->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get($this->invalidVerificationVerifyRoute($user));
        $response->assertStatus(403);
    }

    public function testUserCanVerifyThemselves(): void
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user));
        $response->assertRedirect($this->successfulVerificationRoute());

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function testGuestCannotResendAVerificationEmail(): void
    {
        $response = $this->post($this->verificationResendRoute());
        $response->assertRedirect($this->loginRoute());
    }

    public function testUserIsRedirectedToCorrectRouteIfAlreadyVerified(): void
    {
        $user = factory(User::class)->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->post($this->verificationResendRoute());
        $response->assertRedirect($this->successfulVerificationRoute());
    }

    public function testUserCanResendAVerificationEmail(): void
    {
        Notification::fake();

        $user = factory(User::class)->create(['email_verified_at' => null,]);

        $response = $this->actingAs($user)
            ->from($this->verificationNoticeRoute())
            ->post($this->verificationResendRoute());

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect($this->verificationNoticeRoute());
    }
}
