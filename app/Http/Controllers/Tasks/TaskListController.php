<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskLists\IndexRequest;
use App\Http\Requests\Tasks\TaskLists\StoreRequest;
use App\Models\Tasks\TaskList;
use App\Http\Resources\TaskListCollection;
use App\Http\Resources\TaskListResource;

class TaskListController extends Controller
{
    protected $taskList;

    public function __construct(TaskList $taskList)
    {
        $this->taskList = $taskList;
    }

    public function index(IndexRequest $request): TaskListCollection
    {
        return new TaskListCollection($this->taskList->getItems());
    }

    public function show(TaskList $taskList): TaskListResource
    {
        return $this->taskListResponse($taskList);
    }

    public function store(StoreRequest $request): TaskListResource
    {
        $task = auth()->user()->taskLists()->create($request->validated()['taskList']);

        return $this->taskListResponse($task);

    }

    protected function taskListResponse(TaskList $taskList): TaskListResource
    {
        return new TaskListResource($taskList->load('user', 'items'));
    }
}
