<?php

namespace App\Http\Controllers\Admin\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Tag\StoreRequest;
use App\Http\Resources\Music\Tag\TagResource;
use App\Http\Resources\Music\Tag\TagTreeCollection;
use App\Services\Music\TagService;
use Illuminate\Http\Response;

class TagController extends BaseController
{
    public function __construct(
        private TagService $service
    )
    {
    }

    public function index(): Response
    {
        $out = $this->service->getTags();

        return $this->sendResponse(new TagTreeCollection($out));
    }

    public function store(StoreRequest $request)
    {
        $out = $this->service->storeTag($request->validated());

        return $this->sendResponse(new TagResource($out));
    }
}
