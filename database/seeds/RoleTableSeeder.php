<?php

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Class RoleTableSeeder
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
        factory(Role::class)->create(['name' => 'admin']); 
        factory(Role::class)->create(['name' => 'webmaster']);
    }
}
