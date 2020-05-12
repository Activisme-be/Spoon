<?php

namespace Tests\Feature\Users;

use App\Enums\UserRoles;
use App\Http\Controllers\Users\IndexController;
use App\Http\Requests\Users\InformationValidator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Class StoreMethodTest
 *
 * @package Tests\Feature\Users
 */
class StoreMethodTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private InformationValidator $formRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new InformationValidator();
    }

    private function generateRoles(): void
    {
        $roles = [UserRoles::WEBMASTER, UserRoles::USER, UserRoles::ADMIN];

        foreach ($roles as $key => $role) {
            factory(Role::class)->create(['name' => $role]);
        }
    }

    public function testMiddlewareImplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'store', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testUsesAFormRequest(): void
    {
        $this->assertActionUsesFormRequest(IndexController::class, 'store', InformationValidator::class);
    }

    public function testUnauthorizedResponse(): void
    {
        $this->generateRoles();
        $william = factory(User::class)->create()->assignRole(UserRoles::USER);

        $this->actingAs($william)
            ->get(route('users.show', $william))
            ->assertStatus(403);
    }

    public function testSuccessfulRequest(): void
    {
        $requestData = [
            'voornaam' => $this->faker->firstName,
            'achternaam' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'roles' => [UserRoles::ADMIN, UserRoles::WEBMASTER],
        ];

        $this->generateRoles();
        $william = factory(User::class)->create()->assignRole(['name' => UserRoles::ADMIN]);

        $this->actingAs($william)
            ->post(route('users.store'), $requestData)
            ->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'voornaam' => $requestData['voornaam'], 'achternaam' => $requestData['achternaam'], 'email' => $requestData['email']
        ]);
    }
}
