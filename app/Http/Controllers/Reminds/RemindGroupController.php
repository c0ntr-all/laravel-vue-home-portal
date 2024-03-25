<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Resources\RemindGroups\RemindGroupCollection;
use App\Repositories\Reminds\RemindGroupRepository;

class RemindGroupController extends Controller
{
    public function __construct(
        private RemindGroupRepository $remindGroupRepository
    )
    {
    }

    public function index(IndexRequest $request): RemindGroupCollection
    {
        $items = $this->remindGroupRepository->getList();

        return new RemindGroupCollection($items);
    }
}
