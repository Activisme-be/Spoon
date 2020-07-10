<?php

namespace Tests\Feature\Users;

use App\Enums\UserRoles;
use App\Http\Controllers\Users\IndexController;
use App\Http\Requests\Users\InformationValidator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\Concerns\CanAssertFlash;
use Tests\TestCase;

/**
 * Class UpdateMethodTest
 *
 * @package Tests\Feature\Users
 */
class UpdateMethodTest extends TestCase
{
    use RefreshDatabase;
    use CanAssertFlash;

    private InformationValidator $informationValidator;

    private function requestParameters(): array
    {
        return [
            'voornaam' => 'new-firstname',
            'achternaam' => 'new-lastname',
            'email' => 'new@email.tld',
            'roles' => [UserRoles::WEBMASTER, UserRoles::USER]
        ];
    }

    private function generateUserRoles(): void
    {
        $roles = [UserRoles::ADMIN, UserRoles::USER, UserRoles::WEBMASTER];

        foreach ($roles as $key => $role) {
            factory(Role::class)->create(['name' => $role]);
        }
    }

    public function testMiddlewareImplementation(): void
    {
        $this->assertActionUsesMiddleware(IndexController::class, 'update', [
            'auth', '2fa', 'role:admin|webmaster', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testUpdateMethodUsesAFormrequestClass(): void
    {
        $this->assertActionUsesFormRequest(IndexController::class, 'update', InformationValidator::class);
    }

    public function testResponseForUnauthorizedAccess(): void
    {
        $role    = factory(Role::class)->create(['name' => UserRoles::USER]);
        $tiffany = factory(User::class)->create()->assignRole($role->name);
        $adam    = factory(User::class)->create();

        $this->actingAs($tiffany)
            ->patch(route('users.update', $adam))
            ->assertStatus(403);
    }

    public function testResponseWhenUpdateIsSuccessfull(): void
    {
        $this->generateUserRoles();

        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER]);
        $tiffany = factory(User::class)->create()->assignRole($role->name);
        $harry = factory(User::class)->create();

        $requestData = $this->requestParameters();

        $this->actingAs($tiffany)
            ->patch(route('users.update', $harry), $requestData)
            ->assertRedirect(route('users.show', $harry));

        $this->assertDatabaseMissing('users', $harry->toArray());
        $this->assertDatabaseHas('users', ['voornaam' => $requestData['voornaam'], 'achternaam' => $requestData['achternaam']]);
        $this->assertFlash('success', "De gegevens van New-firstname New-lastname zijn aangepast in de applicatie");
    }

    public function testUpdateMethodWithInvalidId(): void
    {
        $role = factory(Role::class)->create(['name' => UserRoles::WEBMASTER]);
        $tiffany = factory(User::class)->create()->assignRole($role->name);

        $requestData = $this->requestParameters();

        $this->actingAs($tiffany)
            ->patch(route('users.update', ['user' => 1000]), $requestData)
            ->assertStatus(404);
    }
}
