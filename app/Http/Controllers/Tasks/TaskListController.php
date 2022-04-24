<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskLists\IndexRequest;
use App\Http\Requests\Tasks\TaskLists\StoreRequest;
use App\Http\Resources\TaskListCollection;
use App\Http\Resources\TaskListResource;
use App\Models\Tasks\TaskList;
use App\Models\User;

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
        $taskList = auth()->user()->taskLists()->create($request->validated());

        return $this->taskListResponse($taskList);
    }

    protected function taskListResponse(TaskList $taskList): TaskListResource
    {
        return new TaskListResource($taskList->load('user', 'tasks'));
    }

    public function test(IndexRequest $request): TaskListCollection
    {
        return new TaskListCollection($this->taskList->getItems());
    }
}
