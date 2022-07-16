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
     * Возвращает все теги
     *
     * @param IndexRequest $request
     * @return TagCollection
     */
    public function index(IndexRequest $request): TagCollection
    {
        return new TagCollection($this->tag->getItems());
    }

    public function store(StoreRequest $request)
    {
        $existTag = Tag::where(['name' => $request->validated()['tag']])->get();
        if ($existTag->isEmpty()) {
            $result = Tag::create([
                'user_id' => auth()->id(),
                'name' => $request->validated()['tag'],
                'slug' => Str::slug($request->validated()['tag'])
            ]);

            return $this->TagResponse($result);
        } else {
            return ['success' => false, 'error' => ['Такой тег уже существует!']];
        }
    }

    public function tagResponse(Tag $tag): array
    {
        $resource = new TagResource($tag);

        return ['success' => true, 'tags' => $resource];
    }
}
