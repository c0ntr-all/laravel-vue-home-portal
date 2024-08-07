<?php

namespace App\Http\Resources\Reminds;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RemindCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'count' => $this->count(),
            'items' => $this->collection,
        ];
    }
}
