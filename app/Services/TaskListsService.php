<?php

namespace App\Services;

use App\Models\Tasks\TaskList;
use App\Models\Tasks\TaskItem;

class TaskListsService
{
    protected $taskLists;
    protected $taskItems;

    public function __construct(TaskList $taskLists, TaskItem $taskItems)
    {
        $this->taskLists = $taskLists;
        $this->taskItems = $taskItems;
    }
}
