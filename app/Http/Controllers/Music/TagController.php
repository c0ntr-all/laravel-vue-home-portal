<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Tag\IndexRequest;
use App\Http\Requests\Music\Tag\StoreRequest;
use App\Http\Requests\Music\Tag\UpdateRequest;
use App\Http\Resources\Music\Tag\TagTreeCollection;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagSelectCollection;
use App\Models\Music\Tag;
use App\Services\Music\TagService;

class TagController extends Controller
{
    public Tag $tag;
    public TagService $tagService;

    public function __construct(Tag $tag, TagService $service)
    {
        $this->tag = $tag;
        $this->tagService = $service;
    }

    /**
     * @param IndexRequest $request
     * @return TagCollection|TagResource
     */
    public function index(IndexRequest $request)
    {
        if (array_key_exists('slug', $request->validated())) {
            return new TagResource(Tag::where('slug', $request->validated()['slug'])->first());
        } else {
            return new TagCollection($this->tag->getItems());
        }
    }

    /**
     * @return TagSelectCollection
     */
    public function tagsSelect(): TagSelectCollection
    {
        return new TagSelectCollection($this->tag->getItems());
    }

    /**
     * @return TagTreeCollection
     */
    public function tagsTree(): TagTreeCollection
    {
        //todo возможно, тут надо сделать отдельный метод на получение сразу всех тегов с parent_id = 0 вместо getItems
        return new TagTreeCollection($this->tag->getItems());
    }

    public function store(StoreRequest $request)
    {
        $existTag = Tag::where(['name' => $request->validated()['tag']])->get();
        if ($existTag->isEmpty()) {
            $result = Tag::create([
                'name' => $request->validated()['tag'],
                'parent_id' => $request->validated()['parent_id'] ?? 0,
                'common' => $request->validated()['common']
            ]);

            return $this->TagResponse($result);
        } else {
            return ['success' => false, 'error' => ['Такой тег уже существует!']];
        }
    }

    public function update(UpdateRequest $request)
    {
        $updated = Tag::where(['id' => $request->validated()['id']])->update($request->validated());

        if ($updated) {
            return $request->validated();
        }
    }

    public function tagResponse(Tag $tag): array
    {
        $resource = new TagResource($tag);

        return ['success' => true, 'tags' => $resource];
    }
}
