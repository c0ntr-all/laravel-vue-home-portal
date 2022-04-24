<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Finances\Finances;
use App\Models\Finances\FinancesShop;
use App\Models\Tasks\TaskList;
use App\Models\Tasks\Task;
use App\Models\Remind;
use App\Models\Comment;


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
        Remind::factory()->count(15)->create();
        Comment::factory()->count(100)->create();
    }
}
