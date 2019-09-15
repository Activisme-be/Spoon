<?php

use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    public const WEBMASTER = 'webmaster';  // Role name for webmasters in the application.
    public const RVB = 'admin';            // Role name for board members in the application.

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        collect($this->organisationMembers())->each(function (array $name): void {
            [$firstName, $lastName] = $name;

            $data = ['voornaam' => $name[0], 'achternaam' => $name[1], 'email' => strtolower($name[0]) . '@activisme.be', 'password' => 'password'];
            $user = $this->createBackUser($data);

            if ($this->isInWebmasterArray($user->email)) {
                $user->assignRole(self::WEBMASTER);
            }

            $user->assignRole(self::RVB);
        });
    }

    /**
     * Determine if the given address is an webmaster in the application.
     *
     * @return bool
     */
    protected function isInWebmasterArray(string $email): bool
    {
        return in_array($email, $this->organisationWebmasters(), true);
    }

    /**
     * The array of email addresses that are webmasters in the application.
     *
     * @return array
     */
    protected function organisationWebmasters(): array
    {
        return ['tim@activisme.be'];
    }

    /**
     * Get the list of the members in the non profit organisation.
     * This list is also used in the creation of the basic logins
     * for the application barebone.
     *
     * @return array
     */
    protected function organisationMembers(): array
    {
        return [['Tim', 'Joosten'], ['Sara', 'Landuyt'], ['Tom', 'Manheaghe']];
    }

    /**
     * Method for creating the actual logins.
     *
     * @param array $attributes
     * @return User
     */
    protected function createBackUser(array $attributes = []): User
    {
        $person = $this->fakerPerson();
        $data = ['voornaam' => $person['firstName'], 'achternaam' => $person['lastName'], 'email' => $person['email'], 'email_verified_at' => now(), 'password' => $this->faker()->password];

        return User::create(array_merge($attributes, $data));
    }

    /**
     * Method for setting up faker in this seed .
     *
     * @param  string|null $locale The country code for the region.
     * @return Generator
     */
    protected function faker(?string $locale = null): Generator
    {
        return Factory::create($locale ?? Factory::DEFAULT_LOCALE);
    }

    /**
     * Method for creating a fake person entity.
     *
     * @param  string $firstName The firstname of the fake identity
     * @param  string $lastName  The lastname of the fake identity
     * @return array
     */
    protected function fakerPerson($firstName = '', $lastName = ''): array
    {
        $firstName = $firstName ?: $this->faker()->firstName();
        $lastName = $lastName ?: $this->faker()->lastName;

        $email = strtolower($firstName) . '.' . strtolower($lastName) . '@activisme.be';

        return compact('firstName', 'lastName', 'email');
    }
}
