<?php

use App\Enums\UserRoles;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        collect($this->organisationMembers())->each(function (array $name): void {
            [$firstName, $lastName] = $name;

            $data = $this->userDataArray($name);
            $user = $this->createBackUser($data);

            if ($this->isInWebmasterArray($user->email)) {
                $user->assignRole(UserRoles::WEBMASTER);
            }

            $user->assignRole(UserRoles::ADMIN);
        });
    }

    private function isInWebmasterArray(string $email): bool
    {
        return in_array($email, $this->organisationWebmasters(), true);
    }

    private function organisationWebmasters(): array
    {
        return ['tim@' . config('mail.mailers.smtp.host')];
    }

    /**
     * Get the list of the members in the non profit organisation.
     * This list is also used in the creation of the basic logins
     * for the application barebone.
     */
    private function organisationMembers(): array
    {
        return config('core.users');
    }

    private function createBackUser(array $attributes = []): User
    {
        $person = $this->fakerPerson();
        $data = ['voornaam' => $person['firstName'], 'achternaam' => $person['lastName'], 'email' => $person['email'], 'email_verified_at' => now(), 'password' => $this->faker()->password];

        return User::create($attributes + $data);
    }

    private function faker(?string $locale = null): Generator
    {
        return Factory::create($locale ?? Factory::DEFAULT_LOCALE);
    }

    private function fakerPerson(string $firstName = '', string $lastName = ''): array
    {
        $firstName = $firstName ?: $this->faker()->firstName();
        $lastName = $lastName ?: $this->faker()->lastName;

        $email = strtolower($firstName) . '.' . strtolower($lastName) . '@' . config('mail.mailers.smtp.host');

        return compact('firstName', 'lastName', 'email');
    }

    private function userDataArray(array $name): array
    {
        return [
            'voornaam' => $name[0],
            'achternaam' => $name[1],
            'email' => strtolower($name[0]) . '@' . config('mail.mailers.smtp.host'),
            'password' => 'password'
        ];
    }
}
