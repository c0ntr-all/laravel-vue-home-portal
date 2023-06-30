<?php

namespace Database\Factories\Reminds;

use App\Models\Reminds\RemindGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class RemindGroupFactory extends Factory
{
    protected $model = RemindGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'name' => $this->faker->word,
            'content' => $this->faker->text,
            'color' => $this->faker->hexColor,
        ];
    }
}
