<?php

namespace Tests\Feature\Domain\Auth\Http\Controllers;

use App\Domain\Auth\Http\Controllers\AccountController;
use App\Domain\Auth\Http\Requests\InformationValidator;
use App\Domain\Auth\Http\Requests\SecurityValidator;
use App\Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\Concerns\CanAssertFlash;
use Tests\TestCase;

/**
 * Class AccountControllerTest
 *
 * @package Tests\Feature\Domain\Auth\Http\Controllers
 */
class AccountControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanAssertFlash;

    /**
     * @testdox Test the middleware flow and view of the account information settings.
     */
    public function testIndexMethod(): void
    {
        $william = factory(User::class)->create();

        $this->get(route('account.settings'))->assertRedirect(route('login'));

        $this->actingAs($william)->get(route('account.settings'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('users.settings.information');

        $this->assertActionUsesMiddleware(AccountController::class, 'index', ['auth', '2fa', 'forbid-banned-user']);
    }

    /**
     * @testdox Test the middleware flow and view for the account security settings.
     */
    public function testIndexSecurityMethod(): void
    {
        $william = factory(User::class)->create();

        $this->get(route('account.security'))->assertRedirect(route('login'));

        $this->actingAs($william)->get(route('account.security'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('users.settings.security');

        $this->assertActionUsesMiddleware(AccountController::class, 'index', ['auth', '2fa', 'forbid-banned-user']);
    }

    /**
     * @testdox Test the form request and logic for updating the account information successfully.
     */
    public function testUpdateInformationMethod(): void
    {
        $william = factory(User::class)->create();
        $requestData = ['voornaam' => $this->faker->firstName, 'achternaam' => $this->faker->lastName, 'email' => $this->faker->email];

        $this->assertActionUsesFormRequest(AccountController::class, 'updateInformation', InformationValidator::class);
        $this->assertActionUsesMiddleware(AccountController::class, 'updateInformation', ['auth', '2fa', 'forbid-banned-user']);

        $this->patch(route('account.settings.info'), $requestData)->assertRedirect(route('login'));

        $this->actingAs($william)
            ->patch(route('account.settings.info'), $requestData)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertFlash('success', 'Uw accunt informatie is met success aangepast.', true);
    }

    /**
     * @testdox Test the form method and logic for updating the account security information.
     */
    public function testUpdateSecurityMethod(): void
    {
        $william = factory(User::class)->create(['password' => $password = 'password']);
        $requestData = ['huidig_wachtwoord' => $password, 'wachtwoord' => 'new-password', 'wachtwoord_confirmation' => 'new-password'];

        $this->assertActionUsesFormRequest(AccountController::class, 'updateSecurity', SecurityValidator::class);
        $this->assertActionUsesMiddleware(AccountController::class, 'updateSecurity', ['auth', '2fa', 'forbid-banned-user']);

        $this->patch(route('account.settings.security'), $requestData)->assertRedirect(route('login'));

        $this->actingAs($william)
            ->patch(route('account.settings.security'), $requestData)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertFlash('success', 'Uw account beveiliging is met success aangepast.', true);
    }
}
