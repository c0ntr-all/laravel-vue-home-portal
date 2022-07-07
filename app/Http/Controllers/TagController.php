<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Http\Requests\Tag\IndexRequest;
use App\Models\Tag;

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
        return $this->tagResponse($this->tag);
    }

    public function tagResponse(Tag $tag): TagCollection
    {
        return new TagCollection($tag);
    }
}
