<?php

namespace Database\Factories\Tasks;

use App\Models\Tasks\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'list_id' => rand(1, 10),
            'title' => $this->faker->word,
            'content' => $this->faker->realText(50, 2),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
