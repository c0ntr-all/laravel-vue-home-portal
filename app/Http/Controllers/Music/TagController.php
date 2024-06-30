<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Music\Tags\UpdateRequest;
use App\Http\Resources\Client\Music\Tag\TagSelectCollection;
use App\Http\Resources\Client\Music\Tags\TagResource;
use App\Models\Music\MusicTag;
use App\Services\Client\Music\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TagController extends BaseController
{
    public function __construct(
        MusicTag $tag,
        private TagService $tagService
    )
    {
        $this->tag = $tag;
    }

    /**
     * @return JsonResponse|Response
     */
    public function index(): JsonResponse|Response
    {
        $tags = $this->tagService->getTags();

        return $this->response($tags);
    }

    public function show(MusicTag $tag): TagResource
    {
        return new TagResource($tag);
    }

    public function update(MusicTag $tag, UpdateRequest $request)
    {
        $updated = $tag->update($request->validated());

        if ($updated) {
            return $this->tagResponse($tag);
        }
    }

    public function delete(MusicTag $tag)
    {
        $tagName = $tag->name;
        $deleted = $tag->delete();

        if ($deleted) {
            return [
                'success' => true,
                'tag' => $tagName,
                'message' => 'Tag ' . $tagName . ' has been successfully removed!'
            ];
        } else {
            throw new \Exception('Something wrong with deleting tag!');
        }
    }

    public function select(): Response
    {
        $out = $this->tagService->getTags();

        return $this->sendResponse(new TagSelectCollection($out));
    }

    public function tagResponse(MusicTag $tag): array
    {
        $resource = new TagResource($tag);

        return ['success' => true, 'tags' => $resource];
    }
}
