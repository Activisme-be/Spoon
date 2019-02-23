<?php


use Spatie\Seeders\DatabaseSeeder as BaseDatabaseSeeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends BaseDatabaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();
        $this->call(UserTableSeeder::class);
    }
}
