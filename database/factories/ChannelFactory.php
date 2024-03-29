<?php

namespace Database\Factories;

use App\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChannelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Channel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    	$name = $this->faker->word;

    	return [
    		'name' => $name,
    		'slug' =>  Str::slug($name),
    		'description' => $this->faker->paragraph,
    		'archive' => false
    	];
    }
}
