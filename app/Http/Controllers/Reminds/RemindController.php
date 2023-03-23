<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminds\IndexRequest;
use App\Http\Requests\Reminds\StoreRequest;
use App\Http\Requests\Reminds\UpdateRequest;
use App\Http\Resources\RemindCollection;
use App\Http\Resources\RemindResource;
use App\Models\Reminds\Remind;
use R64\NovaFields\Boolean;

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
