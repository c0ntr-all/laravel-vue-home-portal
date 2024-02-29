<?php

namespace App\Http\Resources\Client\Music\Tag;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TagCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'count' => $this->collection->count(),
            'items' => $this->collection
        ];
    }
}
