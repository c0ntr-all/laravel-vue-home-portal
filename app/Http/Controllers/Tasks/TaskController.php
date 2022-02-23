<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\Tasks\StoreRequest;
use App\Http\Requests\Tasks\Tasks\UpdateRequest;
use App\Models\User;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskList;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected Task $task;
    protected TaskList $taskList;
    protected TaskService $taskService;
    protected User $user;

    public function __construct(Task $task, TaskList $taskList, User $user)
    {
        $this->task = $task;
        $this->taskList = $taskList;
        $this->user = $user;
    }
    protected function taskResponse(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function store(StoreRequest $request): TaskResource
    {
//        dd($request->validated());
        $task = Task::create($request->validated());

        return $this->taskResponse($task);
    }

    public function update(Task $task, UpdateRequest $request): TaskResource
    {
        $task->update($request->validated());

        return $this->taskResponse($task);
    }
}
