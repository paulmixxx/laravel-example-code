<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Ramsey\Uuid\Uuid;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'code' => Uuid::uuid4(),
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123123'),
        'remember_token' => str_random(60),
        'role_id' => $faker->randomElement([1, 2, 3, 4]),
        'status_id' => $faker->randomElement([1, 2, 3]),
    ];
});
