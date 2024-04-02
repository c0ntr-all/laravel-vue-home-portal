<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Requests\Reminds\StoreRequest;
use App\Http\Requests\Reminds\UpdateRequest;
use App\Http\Resources\Reminds\RemindCollection;
use App\Http\Resources\Reminds\RemindResource;
use App\Models\Reminds\Remind;
use App\Repositories\Reminds\RemindRepository;
use App\Services\RemindService;

class RemindController extends Controller
{
    public function __construct(
        private RemindRepository $remindRepository,
        private RemindService $remindService
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

        return new RemindResource($newRemind);
    }

    public function update(Remind $remind, UpdateRequest $request): RemindResource
    {
        $updatedRemind = $this->remindService->updateRemind($remind, $request->validated());

        $updatedRemind->load('group');

        return new RemindResource($updatedRemind);
    }
}
