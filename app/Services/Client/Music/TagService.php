<?php declare(strict_types=1);

namespace App\Services\Client\Music;

use App\Http\Resources\Client\Music\Tag\TagCollection;
use App\Models\Music\MusicTag;
use App\Repositories\TagRepository;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository
    )
    {
    }

    public function getTags(): TagCollection
    {
        $tags = $this->tagRepository->getTags();

        return new TagCollection($tags);
    }

    public function storeTag($requestData)
    {
        $requestData['parent_id'] = array_key_exists('parent_id', $requestData) ? $requestData['parent_id'] : NULL;

        if ($requestData['parent_id']) {
            $parentTag = MusicTag::find($requestData['parent_id']);
            $requestData['common'] = $parentTag->common;
        }

        return MusicTag::create($requestData);
    }
}
