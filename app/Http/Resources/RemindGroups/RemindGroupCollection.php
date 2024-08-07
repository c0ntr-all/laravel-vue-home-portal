<?php

namespace App\Http\Resources\RemindGroups;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RemindGroupCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'count' => $this->count(),
            'items' => $this->collection,
        ];
    }
}
