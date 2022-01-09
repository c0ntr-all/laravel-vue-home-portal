<?php

namespace Database\Factories;

use App\Models\Finances\Finances;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Finances::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->numberBetween($min = 10, $max = 60000);
        $comment = $this->faker->realText(rand(20, 100));
        $createdAt = $this->faker->dateTimeBetween('-10 months', 'now');
        $doneInterval = '+ ' . rand(0, 180) . ' minutes';
        $doneAt = $this->faker->dateTimeInInterval($createdAt, $doneInterval);
        $types = ['income', 'outcome'];

        return [
            'type' => $types[array_rand($types, 1)],
            'user_id' => rand(1,2),
            'summ' => $title,
            'recipient' => rand(1,10),
            'comment' => $comment,
            'done_at' => $doneAt,
            'created_at' => $createdAt,
        ];
    }
}
