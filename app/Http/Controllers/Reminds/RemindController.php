<?php

namespace App\Http\Controllers\Reminds;

use App\Enums\Reminds\RemindIntervalEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Requests\Reminds\StoreRequest;
use App\Http\Requests\Reminds\UpdateRequest;
use App\Http\Resources\Reminds\RemindCollection;
use App\Http\Resources\Reminds\RemindResource;
use App\Models\Reminds\Remind;
use App\Repositories\Reminds\RemindRepository;
use App\Services\Client\Reminds\RemindService;
use Illuminate\Http\Response;

class RemindController extends Controller
{
    public function __construct(
        private readonly RemindRepository $remindRepository,
        private readonly RemindService    $remindService
    )
    {
    }

    public function index(IndexRequest $request): RemindCollection
    {
        $items = $this->remindRepository->getList();

        return new RemindCollection($items);
    }

    public function store(StoreRequest $request): RemindResource
    {
        $newRemind = $this->remindService->createRemind($request->validated());

        $newRemind->load('group');

        return new RemindResource($newRemind);
    }

    public function update(Remind $remind, UpdateRequest $request): RemindResource
    {
        $updatedRemind = $this->remindService->updateRemind($remind, $request->validated());

        $updatedRemind->load('group');

        return new RemindResource($updatedRemind);
    }

    public function delete(Remind $remind): Response
    {
        $this->remindService->deleteRemind($remind);

        return response(['message' => 'Remind deleted successfully!']);
    }

    public function getIntervals(): Response
    {
        return response($this->remindService->getIntervals());
    }
}
