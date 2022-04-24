<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-3 months', '-2 months');

        return [
            'user_id' => 1,
            'comment_id' => rand(1, 100), //Столько генерится тасков в фабрике тасков
            'comment_type' => 'App\\Models\\Tasks\\Task',
            'content' => $this->faker->word,
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
