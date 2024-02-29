<?php

namespace App\Http\Resources\Admin\Music\Tag;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TagTreeCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'items' => [
                'common' => $this->collection->filter(fn ($tag) => $tag->common),
                'secondary' => $this->collection->filter(fn ($tag) => !$tag->common)
            ],
            'count' => $this->collection->count()
        ];
    }
}
