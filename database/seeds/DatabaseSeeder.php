<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class DatabaseSeeder.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        DB::connection()->disableQueryLog();
        Model::unguard();

        $this->truncateAll();

        // Run database seeders
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }

    /**
     * Method that let us truncate all the database tables.
     *
     * @return void
     */
    protected function truncateAll(): void
    {
        Schema::disableForeignKeyConstraints();

        collect(DB::select("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'"))
            ->map(static function ($tableProperties) {
                return get_object_vars($tableProperties)[key($tableProperties)];
            })
            ->reject(static function (string $tableName) {
                return $tableName === 'migrations';
            })
            ->each(static function (string $tableName) {
                DB::table($tableName)->truncate();
            });

        Schema::enableForeignKeyConstraints();
    }
}
