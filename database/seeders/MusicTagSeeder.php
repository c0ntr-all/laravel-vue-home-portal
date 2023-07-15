<?php

namespace Database\Seeders;

use App\Models\Music\MusicTag;
use Illuminate\Database\Seeder;

class MusicTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MusicTag::factory()->has(MusicTag::factory()->count(3), 'children')->count(30)->create();
    }
}
