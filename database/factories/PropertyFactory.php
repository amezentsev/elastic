<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Property;
use Faker\Generator as Faker;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'value' =>  $faker->sentence(),
        'good_id' => rand(1, \App\Models\Good::count()),
    ];
});
