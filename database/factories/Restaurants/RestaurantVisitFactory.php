<?php

namespace Database\Factories\Restaurants;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantVisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'restaurant_id' => rand(1, 15),
            'datetime' => $this->faker->dateTimeBetween('-10 months', 'now'),
            'food' => json_encode([
                'food' => 'test food',
                'rank' => 4,
                'images' => [
                    'tmp/image1.jpg',
                    'tmp/image2.jpg',
                    'tmp/image3.png',
                ]
            ]),
        ];
    }
}
