<?php

namespace App\Http\Resources\Client\Music\Albums\Page\Versions;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AlbumVersionCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'count' => $this->count(),
            'items' => $this->collection
        ];
    }
}
