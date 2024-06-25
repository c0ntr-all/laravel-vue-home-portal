<?php

namespace App\Http\Resources\Admin\Music\Tag;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TagTreeCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'items' => [
                'base' => $this->collection->filter(fn ($tag) => $tag->is_base),
                'secondary' => $this->collection->filter(fn ($tag) => !$tag->is_base)
            ],
            'count' => $this->collection->count()
        ];
    }
}
