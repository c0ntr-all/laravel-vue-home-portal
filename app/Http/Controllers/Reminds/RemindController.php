<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Models\Remind;
use App\Http\Resources\RemindCollection;
use App\Http\Resources\RemindResource;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Requests\Reminds\StoreRequest;

class RemindController extends Controller
{
    protected $reminds;

    public function __construct(Remind $reminds)
    {
        $this->reminds = $reminds;
    }

    public function index(IndexRequest $request): RemindCollection
    {
        return new RemindCollection($this->reminds->getitems());
    }

    public function store(StoreRequest $request): RemindResource
    {
        $remind = auth()->user()->reminds()->create($request->validated());

        return $this->remindResponse($remind);
    }
    protected function remindResponse(Remind $remind): RemindResource
    {
        return new RemindResource($remind);
    }
}
