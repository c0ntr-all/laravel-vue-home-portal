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
            'success' => true,
            'tags' => [
                'common' => $this->prepareTags(),
                'secondary' => $this->prepareTags(false)
            ],
            'tagsCount' => $this->count()
        ];
    }

    private function prepareTags($common = true): Collection
    {
        return $this->collection->where('common', $common)
                                ->map(function ($item) {
                                    return [
                                        'label' => $item->name,
                                        'value' => $item->id
                                    ];
                                });
    }
}
