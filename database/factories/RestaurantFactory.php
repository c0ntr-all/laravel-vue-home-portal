<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(rand(5, 15)),
            'address' => $this->faker->address(),
            'image' => $this->faker->image($dir = '/tmp', $width = 640, $height = 480),
        ];
    }
}
