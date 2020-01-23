<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Activity;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'start' => (new DateTime($faker->date()))->add(new DateInterval('P' . $faker->numberBetween(1, 21) . 'D')) ,
        'resource_id' => $faker->numberBetween(),
        'rule_id' => $faker->numberBetween(),
        'duration' => $faker->numberBetween(1, 21),
        'r' => $faker->numberBetween(0, 255),
        'g' => $faker->numberBetween(0, 255),
        'b' => $faker->numberBetween(0, 255),
    ];
});

