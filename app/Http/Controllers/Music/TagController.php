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
    public MusicTag $tag;
    public TagService $tagService;

    public function __construct(MusicTag $tag, TagService $service)
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
            return new TagResource(MusicTag::where('slug', $request->validated()['slug'])->first());
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
        $existTag = MusicTag::where(['name' => $request->validated()['name']])->get();
        if ($existTag->isEmpty()) {
            $data = $request->validated();

            $common = $data['common'] ?? 0;
            $parentId = $data['parent_id'];

            if ($parentId) {
                $parentTag = MusicTag::find($parentId);
                $common = $parentTag->common;
            }

            $data['common'] = $common;
            $data['parent_id'] = $data['parent_id'] ?? 0;

            $result = MusicTag::create($data);

            return $this->TagResponse($result);
        } else {
            return ['success' => false, 'error' => ['Такой тег уже существует!']];
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
