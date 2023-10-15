<?php

namespace Database\Seeders\Music;

use App\Models\Music\Playlist;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlaylistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {
            Playlist::factory()->count(5)->withParameters($user->id)->create();
        });
    }
}
