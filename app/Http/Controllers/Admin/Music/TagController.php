<?php

namespace App\Http\Controllers\Admin\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Tag\StoreRequest;
use App\Http\Resources\Music\Tag\TagResource;
use App\Services\Music\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TagController extends BaseController
{
    public function __construct(
        private TagService $tagService
    )
    {
    }

    public function index(): JsonResponse|Response
    {
        $data = $this->tagService->getTagsTree();

        return $this->response($data);
    }

    public function store(StoreRequest $request)
    {
        $out = $this->tagService->store($request->validated());

        return $this->sendResponse(new TagResource($out));
    }
}
