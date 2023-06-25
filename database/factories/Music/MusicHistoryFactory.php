<?php

namespace Database\Factories\Music;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class MusicHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $usersIds = DB::table('users')->get()->pluck('id')->toArray();
        $date = $this->faker->dateTimeBetween('-1 month', 'now');

        return [
            'user_id' => $usersIds[rand(1, count($usersIds))],
            'track_id' => rand(1, 100),
            'created_at' => $date,
            'updated_at' => $date,
            'deleted_at' => NULL,
        ];
    }
}
