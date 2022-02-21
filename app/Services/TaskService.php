<?php

namespace App\Services;

use App\Models\Tasks\Task;

class TaskService
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
