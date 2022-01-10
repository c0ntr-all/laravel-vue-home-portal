<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Finances\Finances;
use App\Models\Finances\FinancesShop;


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
    }
}
