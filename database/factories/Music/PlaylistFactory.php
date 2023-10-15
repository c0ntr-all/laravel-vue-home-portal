<?php

namespace Database\Factories\Music;

use App\Models\Music\Track;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Music\Playlist;

class PlaylistFactory extends Factory
{
    protected $model = Playlist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [];
    }

    /**
     * Define the model's state with parameters.
     *
     * @param string $userId
     * @return Factory
     */
    public function withParameters(string $userId)
    {
        return $this->state([
            'user_id' => $userId,
            'name' => $this->faker->word,
            'content' => $this->faker->text,
        ]);
    }
}
