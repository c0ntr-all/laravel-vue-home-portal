<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class TagCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'tags' => $this->prepareTags(),
            'tagsCount' => $this->count()
        ];
    }

    private function prepareTags(): Collection
    {
        return $this->collection->map(function($item){
            return [
                'id' => $item->id,
                'label' => $item->name,
                'slug' => $item->slug,
                'type' => '',
                'createdAt' => $item->created_at
            ];
        });
    }
}
