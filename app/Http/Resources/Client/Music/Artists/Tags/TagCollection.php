<?php

namespace App\Http\Resources\Client\Music\Artists\Tags;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Будет применяться ко всем спискам тегов в музыке, кроме основного раздела
 */
class TagCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'ids' => $this->collection->pluck('id'),
            'names' => $this->collection->pluck('name')
        ];
    }
}
