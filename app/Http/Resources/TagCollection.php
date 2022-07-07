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
        return $this->collection->get()->unique('tag')->map(function($item){
            return ['label' => $item->tag, 'type' => ''];
        });
    }
}
