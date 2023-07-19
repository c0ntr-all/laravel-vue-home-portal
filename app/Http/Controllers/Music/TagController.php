<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Tag\IndexRequest;
use App\Http\Requests\Music\Tag\StoreRequest;
use App\Http\Requests\Music\Tag\UpdateRequest;
use App\Http\Resources\Music\Tag\TagCollection;
use App\Http\Resources\Music\Tag\TagResource;
use App\Http\Resources\Music\Tag\TagSelectCollection;
use App\Http\Resources\Music\Tag\TagTreeCollection;
use App\Models\Music\MusicTag;
use App\Services\Music\TagService;

class TagController extends Controller
{
    public function __construct(
        MusicTag $tag,
        private TagService $service
    )
    {
        $this->tag = $tag;
    }

    /**
     * @param IndexRequest $request
     * @return TagCollection|TagResource
     */
    public function index(IndexRequest $request)
    {
        if (array_key_exists('slug', $request->validated())) {
            return new TagResource(MusicTag::where('slug', $request->validated()['slug'])->first());
        } else {
            return new TagCollection($this->tag->getItems());
        }
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

    public function tagResponse(MusicTag $tag): array
    {
        $resource = new TagResource($tag);

        return ['success' => true, 'tags' => $resource];
    }
}
