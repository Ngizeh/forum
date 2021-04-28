<?php

namespace Database\Factories;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    	$title = $this->faker->sentence;

    	return [
    		'user_id' => function(){
    			return User::factory()->create()->id;
    		},
    		'channel_id' => function(){
    			return Channel::factory()->create()->id;
    		},
    		'title' => $title,
    		'body' => $this->faker->paragraph,
    		'slug' => Str::slug($title),
    		'locked' => false
    	];
    }
}
