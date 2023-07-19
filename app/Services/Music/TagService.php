<?php

namespace App\Services\Music;

use App\Models\Music\MusicTag;
use Illuminate\Support\Collection;

class TagService
{
    public function __construct()
    {
    }

    public function getTags(): Collection
    {
        return MusicTag::orderBy('name')->get();
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
