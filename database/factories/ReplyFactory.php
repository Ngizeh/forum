<?php

use App\User;
use App\Thread;
use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'thread_id' => function () {
            return factory(Thread::class)->create()->id;
        },
        'body' => $faker->paragraph
    ];
});
