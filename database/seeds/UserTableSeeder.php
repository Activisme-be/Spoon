<?php

use Spatie\Seeders\Faker;
use App\User;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder 
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        collect($this->organisationMembers())
            ->each(function (array $name) {
                [$firstName, $lastName] = $name;
                $this->createBackUser([
                    'voornaam' => $name[0],
                    'achternaam' => $name[1],
                    'email' => strtolower($name[0]) . '@activisme.be', 
                    'password' => bcrypt('secret'),
                ]);
            });
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
        return [
            ['Tim', 'Joosten'],
            ['Sara', 'Landuyt'], 
            ['Tom', 'Manheaghe'],
        ];
    }

    /**
     * Method for creating the actual logins. 
     * 
     * @param  array $attributes 
     * @return User
     */
    public function createBackUser(array $attributes = []): User
    {
        $person = app(Faker::class)->person();

        return User::create($attributes + [
            'voornaam' => $person['firstName'],
            'achternaam' => $person['lastName'],
            'email' => $person['email'],
            'email_verified_at' => now(),
            'password' => faker()->password,
        ]);
    }
}
