<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Tasks\Tasks\StoreRequest;
use App\Http\Requests\Tasks\Tasks\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskList;

class TaskController extends BaseController
{
    public function __construct()
    {
    }

    public function store(StoreRequest $request, TaskList $taskList): TaskResource
    {
        $task = $taskList->tasks()->create($request->validated());

        return $this->taskResponse($task);
    }

    public function update(Task $task, UpdateRequest $request): TaskResource
    {
        $task->update($request->validated());

        return $this->taskResponse($task);
    }

    protected function taskResponse(Task $task): TaskResource
    {
        return new TaskResource($task->load(['comments']));
    }
}
