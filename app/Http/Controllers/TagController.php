<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Http\Requests\Tag\IndexRequest;
use App\Http\Requests\Tag\StoreRequest;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Возвращает все уникальные теги
     *
     * @param IndexRequest $request
     * @return TagCollection
     */
    public function index(IndexRequest $request): TagCollection
    {
        return new TagCollection($this->tag->getItems());
    }

    public function store(StoreRequest $request, Tag $tag)
    {
        $result = $tag->create([
            'user_id' => auth()->id(),
            'name' => $request->validated()['tag'],
            'slug' => Str::slug($request->validated()['tag'])
        ]);

        return $this->TagResponse($result);
    }

    public function tagResponse(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }
}
