<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Tasks\IndexRequest;
use App\Models\Tasks\TaskList;
use App\Http\Resources\TaskListCollection;
use App\Http\Resources\TaskListResource;

class TaskListController extends Controller
{
    protected $taskLists;

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

    protected function taskListResponse(TaskList $taskList): TaskListResource
    {
        return new TaskListResource($taskList->load('user', 'items'));
    }
}
