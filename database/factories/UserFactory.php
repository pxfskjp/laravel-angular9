<?php

use Faker\Generator as Faker;

$user = app()->make(\App\Data\Models\User::class);

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

$factory->define(get_class($user), function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5),
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => null,
    ];
});
