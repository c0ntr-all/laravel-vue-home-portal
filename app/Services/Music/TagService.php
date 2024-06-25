<?php

namespace App\Services\Music;

use App\Http\Resources\Admin\Music\Tag\TagTreeCollection;
use App\Models\Music\MusicTag;
use App\Repositories\TagRepository;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository
    )
    {
    }

    public function getTagsTree(): TagTreeCollection
    {
        $tags = $this->tagRepository->getTagsTree();

        return new TagTreeCollection($tags);
    }

    public function store($requestData)
    {
        $requestData['parent_id'] = array_key_exists('parent_id', $requestData) ? $requestData['parent_id'] : NULL;

        if ($requestData['parent_id']) {
            $parentTag = MusicTag::find($requestData['parent_id']);
            $requestData['is_base'] = $parentTag->is_base;
        }

        return MusicTag::create($requestData);
    }
}
