<?php

namespace Database\Factories\Finances;

use App\Models\Finances\FinancesShop;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancesShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinancesShop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'image' => $this->faker->hexColor(),
            'value' => $this->faker->word,
            'update_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
