<?php

use App\User;
use App\Channel;
use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function(){
        	return factory(User::class)->create()->id;
        },
        'channel_id' => function(){
        	return factory(Channel::class)->create()->id;
        },
        'title' => $title,
        'body' => $faker->paragraph,
        'slug' => str_slug($title),
        'locked' => false
    ];
});
