<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Tasks\IndexRequest;
use App\Models\Tasks\TaskList;
use App\Http\Resources\TaskListsCollection;
use App\Http\Resources\TaskListsResource;

class TaskListController extends Controller
{
    protected $taskLists;

    public function __construct(TaskList $taskLists)
    {
        $this->taskLists = $taskLists;
    }

    public function index(IndexRequest $request): TaskListsCollection
    {
        return new TaskListsCollection($this->taskLists->getItems());
    }

    public function show(TaskList $taskLists): TaskListsResource
    {
        return $this->taskListsResponse($taskLists);
    }

    protected function taskListsResponse(TaskList $taskLists): TaskListsResource
    {
        return new TaskListsResource($taskLists->load('user', 'items'));
    }
}
