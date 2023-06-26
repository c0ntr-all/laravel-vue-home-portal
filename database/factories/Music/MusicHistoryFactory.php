<?php

namespace Database\Factories\Music;

use App\Models\Music\MusicHistory;
use App\Models\Music\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicHistoryFactory extends Factory
{
    protected $model = MusicHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-1 month', 'now');
        $tracks = Track::all();

        return [
            'user_id' => User::all()->random()->id,
            'track_id' => $tracks->random()->id,
            'created_at' => $date,
            'updated_at' => $date,
            'deleted_at' => NULL,
        ];
    }
}
