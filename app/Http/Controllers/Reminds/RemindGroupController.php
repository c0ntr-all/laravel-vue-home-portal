<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Requests\RemindsGroups\SaveRequest;
use App\Http\Resources\RemindGroups\RemindGroupCollection;
use App\Models\Reminds\RemindGroup;
use App\Repositories\Reminds\RemindGroupRepository;
use App\Services\Client\Reminds\RemindGroupService;
use Illuminate\Http\Response;

class RemindGroupController extends Controller
{
    public function __construct(
        private readonly RemindGroupRepository $remindGroupRepository,
        private readonly RemindGroupService $remindGroupService
    )
    {
    }

    public function index(IndexRequest $request): Response
    {
        $items = $this->remindGroupRepository->getList();

        return response(new RemindGroupCollection($items), 200);
    }

    public function delete(RemindGroup $remindGroup): Response
    {
        $this->remindGroupService->deleteRemindGroup($remindGroup);

        return response(['message' => 'Remind group deleted successfully!']);
    }

    public function save(SaveRequest $request): Response
    {
        $remind = $this->remindGroupService->saveRemindGroup($request->validated());

        return response(['message' => 'Remind group saved successfully!', 'data' => $remind]);
    }
}
