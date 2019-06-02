<?php

namespace Tests\Concerns;

use App\User;
use Spatie\Permission\Models\Role;

/**
 * Helper trait for creating dummy users while testing.
 *
 * @author      Tim Joosten <tim@activisme.be>
 * @copyright   ctivisme_BE
 * @package     Tests\Traits
 */
trait UsersTrait
{
    /**
     * Function for creating a newly role in the testing database.
     *
     * @param  string $name The name for the role that need to be created.
     * @return string
     */
    protected function createRole(string $name): string
    {
        return factory(Role::class)->create(['name' => $name])->name;
    }

    /**
     * Create an normal user in the testing database.
     *
     * @param  string $role The name from the access role.
     * @return User
     */
    public function createUser(string $role): User
    {
        return factory(User::class)->create()->assignRole($this->createRole($role));
    }

    /**
     * Create a blokced user in the testing database.
     *
     * @return User
     */
    public function createUserBlocked(): User
    {
        $role = $this->createRole('admin');
        $user = factory(User::class)->create()->assignRole($role)->ban();

        return User::find($user->id);
    }
}
