<?php

namespace Database\Seeders;

use App\Models\Comment;
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
use Database\Seeders\Music\PlaylistTableSeeder;
use Database\Seeders\Widgets\WidgetPlacementSeeder;
use Database\Seeders\Widgets\WidgetSeeder;
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
        User::factory()->count(10)->create()->each(function ($user) {
            $remindsGroups = RemindGroup::factory()->count(5)->create();
            $reminds = Remind::factory()->count(15)->create();
            $reminds->each(function ($remind) use ($remindsGroups) {
                $remind->group_id = $remindsGroups->random()->id;
                $remind->save();
            });
            $user->remindsGroups()->saveMany($remindsGroups);
            $user->reminds()->saveMany($reminds);
        });

        TaskList::factory()->count(10)->create();
        Task::factory()->count(50)->create();

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

        $this->call([
            PlaylistTableSeeder::class,
            WidgetSeeder::class,
            WidgetPlacementSeeder::class
        ]);
    }
}
