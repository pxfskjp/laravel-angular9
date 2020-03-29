<?php

use Faker\Generator as Faker;

$factory->define(\App\Data\Models\Hardware::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5),
        'name' => $faker->company,
        'serial_number' => $faker->uuid,
        'production_year' => $faker->year,
    ];
});
