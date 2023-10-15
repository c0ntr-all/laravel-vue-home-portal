<?php

namespace Database\Seeders\Widgets;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('widgets')->insert([
            'name' => 'Музыка',
            'model' => 'App\Models\Widgets\Models\MusicWidget',
            'description' => 'Виджет музыки',
        ]);
        DB::table('widgets')->insert([
            'name' => 'Напоминания',
            'model' => 'App\Models\Widgets\Models\RemindsWidget',
            'description' => 'Виджет напоминаний',
        ]);
    }
}
