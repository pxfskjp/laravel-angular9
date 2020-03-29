<?php
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(\App\Data\Models\User\Token::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'secret' => $faker->uuid,
        'type' => null,
        'created_at' => Carbon::now()
    ];
});
