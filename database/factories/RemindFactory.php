<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RemindFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isActive = rand(1, 2) > 1;

        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 months');
        return [
            'user_id' => 1,
            'title' => $this->faker->word,
            'is_active' => $isActive,
            'datetime' => $this->faker->dateTimeBetween('2022-04-20', '2022-12-31'),
            'created_at'   => $createdAt,
            'updated_at'   => $createdAt
        ];
    }
}
