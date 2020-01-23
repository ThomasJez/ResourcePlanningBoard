<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ganttconfig;
use Faker\Generator as Faker;

$factory->define(Ganttconfig::class, function (Faker $faker) {
    return [
        'id' => $faker->text(50),
        'value' => $faker->date('Y-m-d'),
    ];
});

$factory->state(Ganttconfig::class, 'start', function (Faker $faker) {
    return [
        'id' => 'start',
    ];
});

$factory->state(Ganttconfig::class, 'resource_term', function (Faker $faker) {
    return [
        'id' => 'resource_term',
        'value' => $faker->text,
    ];
});

$factory->state(Ganttconfig::class, 'rule_term', function (Faker $faker) {
    return [
        'id' => 'rule_term',
        'value' => $faker->text,
    ];
});

