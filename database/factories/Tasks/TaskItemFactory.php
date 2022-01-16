<?php

namespace Database\Factories\Tasks;

use App\Models\Tasks\TaskItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskItemFactory extends Factory
{
    protected $model = TaskItem::class;

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
