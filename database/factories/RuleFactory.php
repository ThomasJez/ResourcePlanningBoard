<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rule;
use Faker\Generator as Faker;

$factory->define(Rule::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'duration' => $faker->numberBetween(1, 21),
        'r' => $faker->numberBetween(0, 255),
        'g' => $faker->numberBetween(0, 255),
        'b' => $faker->numberBetween(0, 255),
        'pos' => $faker->numberBetween()
    ];
});

