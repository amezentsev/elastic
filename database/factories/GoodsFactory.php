<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Good;
use Faker\Generator as Faker;

$factory->define(Good::class, function (Faker $faker) {
    $categories = collect(['shoes', 'dress', 'gloves', 'hats', 'jeans', 'socks'])
        ->random(2)
        ->values()
        ->all();
    return [
        'name' => $faker->sentence(),
        'description' => $faker->text(),
        'quantity' => $faker->randomNumber(),
        'categories' => $categories
    ];
});
