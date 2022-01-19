<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreRequest;
use App\Models\User;
use App\Models\Tasks\TaskItem;
use App\Http\Resources\TaskItemsResource;

class TaskItemController extends Controller
{
    public function __construct(TaskItem $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    public function store(StoreRequest $request)
    {
        //$task = auth()->user()->tasks()->create($request->validated()['task']);

        //return $this->taskResponse($task);

    }
    protected function taskResponse(TaskItem $task): TaskItemsResource
    {
        return new TaskItemsResource($task->load('user'));
    }
}
