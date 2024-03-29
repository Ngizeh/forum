<?php

namespace Database\Factories;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    	return [
    		'user_id' => function () {
    			return User::factory()->create()->id;
    		},
    		'thread_id' => function () {
    			return Thread::factory()->create()->id;
    		},
    		'body' => $this->faker->paragraph
    	];
    }
}
