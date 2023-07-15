<?php

namespace Database\Factories\Music;

use App\Models\Music\MusicTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicTagFactory extends Factory
{
    protected $model = MusicTag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(3, true);

        return [
            'name' => $name,
            'slug' => \Str::slug($name),
            'content' => $this->faker->realText(50, 2),
            'common' => $this->faker->boolean,
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now')
        ];
    }
}
