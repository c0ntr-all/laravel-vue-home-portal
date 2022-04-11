<?php

namespace Database\Seeders\Restaurants;

use App\Models\Restaurants\RestaurantVisit;
use Illuminate\Database\Seeder;

class RestaurantVisitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::factory()->count(50)->create();
    }
}
