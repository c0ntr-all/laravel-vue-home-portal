<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Tasks\TaskLists\StoreRequest;
use App\Http\Resources\Task\TaskListCollection;
use App\Http\Resources\Task\TaskListResource;
use App\Models\Tasks\TaskList;
use Illuminate\Http\Response;

class TaskListController extends BaseController
{
    public function __construct(private TaskList $taskList)
    {
    }

    public function index(): Response
    {
        $items = $this->taskList->getItems();

        return $this->sendResponse(new TaskListCollection($items), 'Task lists');
    }

    public function show(TaskList $taskList): Response
    {
        return $this->taskListResponse($taskList);
    }

    public function store(StoreRequest $request): Response
    {
        $taskList = auth()->user()->taskLists()->create($request->validated());

        return $this->taskListResponse($taskList);
    }

    protected function taskListResponse(TaskList $taskList): Response
    {
        $result = new TaskListResource($taskList->load(['user', 'tasks']));

        return $this->sendResponse($result, 'Task lists');
    }
}
