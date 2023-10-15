<?php

namespace Database\Seeders\Widgets;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WidgetPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $users->each(function ($user) {
            DB::table('widgets_placements')->insert([
                'user_id' => $user->id,
                'widget_id' => 1,
                'rank' => 1,
                'size' => 'col-3',
            ]);
            DB::table('widgets_placements')->insert([
                'user_id' => $user->id,
                'widget_id' => 2,
                'rank' => 2,
                'size' => 'col-3',
            ]);
        });
    }
}
