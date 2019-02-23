<?php

use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;

$factory->define(Role::class, function (Faker $faker): array {
    return ['name' => $faker->name];
});
