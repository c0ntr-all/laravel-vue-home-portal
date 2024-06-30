<?php

namespace App\Http\Resources\Client\Music\Artists\Page\Albums;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AlbumCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'count' => $this->count(),
            'items' => $this->collection
        ];
    }
}
