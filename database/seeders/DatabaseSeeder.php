<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Finances\Finances;
use App\Models\Finances\FinancesShop;
use App\Models\Music\Album;
use App\Models\Music\Artist;
use App\Models\Music\MusicHistory;
use App\Models\Music\Track;
use App\Models\Reminds\Remind;
use App\Models\Reminds\RemindGroup;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskList;
use App\Models\User;
use App\Models\Music\MusicTag;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        Finances::factory()->count(100)->create();
        FinancesShop::factory()->count(10)->create();
        TaskList::factory()->count(10)->create();
        Task::factory()->count(50)->create();
        RemindGroup::factory()->count(5)->create();
        Remind::factory()->count(15)->create();
        Comment::factory()->count(100)->create();

        MusicTag::factory()
                ->has(MusicTag::factory()->count(3), 'children')
                ->count(30)
                ->create();
        Artist::factory()
              ->has(
                  Album::factory()->has(
                      Track::factory()->count(5), 'tracks'
                  )->count(3), 'albums'
              )
              ->count(10)
              ->create();
        MusicHistory::factory()->count(200)->create();
    }
}
