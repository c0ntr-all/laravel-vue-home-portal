<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class TagSelectCollection extends ResourceCollection
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
                'label' => $item->name,
                'value' => $item->id
            ];
        });
    }
}
