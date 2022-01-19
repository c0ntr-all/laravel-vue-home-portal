<?php

namespace App\Services;

use App\Models\Tasks\TaskList;
use App\Models\Tasks\Task;

class TaskListService
{
    protected $taskList;
    protected $task;

    public function __construct(TaskList $taskList, Task $task)
    {
        $this->taskList = $taskList;
        $this->task = $task;
    }
}
