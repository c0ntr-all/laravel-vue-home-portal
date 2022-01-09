<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finances\FinancesShop;

class FinancesShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FinancesShop::factory()->count(10)->create();
    }
}
