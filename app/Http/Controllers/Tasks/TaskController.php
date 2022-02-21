<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\Tasks\StoreRequest;
use App\Models\User;
use App\Models\Tasks\Task;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }
    protected function taskResponse(Task $task): TaskResource
    {
        return new TaskResource($task->load('user'));
    }

    public function store(StoreRequest $request): TaskResource
    {
        $task = auth()->user()->task()->create($request->validated());

        return $this->taskResponse($task);
    }
}
