<?php

use App\Domain\Auth\Enums\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Class RoleTableSeeder.
 */
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->rolesArray() as $key => $role) {
            factory(Role::class)->create(['name' => $role]);
        }
    }

    private function rolesArray(): array
    {
        return [UserRoles::ADMIN, UserRoles::WEBMASTER, UserRoles::USER];
    }
}
