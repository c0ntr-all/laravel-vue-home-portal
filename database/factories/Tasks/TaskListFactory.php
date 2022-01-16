<?php

namespace Database\Factories\Tasks;

use App\Models\Tasks\TaskList;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskListFactory extends Factory
{
    protected $model = TaskList::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->word,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
