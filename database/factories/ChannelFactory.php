<?php

use Faker\Generator as Faker;

$factory->define(App\Channel::class, function (Faker $faker) {

	$name = $faker->word;

    return [
        'name' => $name,
        'slug' =>  str_slug($name),
        'description' => $faker->paragraph,
        'archive' => false
    ];
});
