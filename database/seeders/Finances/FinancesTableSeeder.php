<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finances\Finances;

class FinancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Finances::factory()->count(100)->create();
    }
}
