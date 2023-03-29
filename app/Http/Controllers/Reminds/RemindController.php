<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Requests\Reminds\StoreRequest;
use App\Http\Requests\Reminds\UpdateRequest;
use App\Http\Resources\RemindCollection;
use App\Http\Resources\RemindResource;
use App\Models\Reminds\Remind;
use App\Services\RemindService;

class RemindController extends Controller
{
    protected $remind;
    protected $remindService;

    public function __construct(Remind $remind, RemindService $remindService)
    {
        $this->remind = $remind;
        $this->remindService = $remindService;
    }

    public function index(IndexRequest $request): RemindCollection
    {
        return new RemindCollection($this->remind->getitems());
    }

    public function store(StoreRequest $request): RemindResource
    {
        $remind = auth()->user()->reminds()->create($request->validated());

        $this->remindService->syncGroup($remind, $request->validated()['group']);

        return $this->remindResponse($remind);
    }

    public function update(Remind $remind, UpdateRequest $request)
    {
        if($remind->update($request->validated())) {
            return $this->remindResponse($remind);
        }
    }

    protected function remindResponse(Remind $remind): RemindResource
    {
        return new RemindResource($remind);
    }
}
