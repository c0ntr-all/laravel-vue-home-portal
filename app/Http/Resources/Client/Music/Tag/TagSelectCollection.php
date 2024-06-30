<?php

namespace App\Http\Resources\Client\Music\Tag;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class TagSelectCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        Collection::macro('prepareTags', function (bool $common) {
            return $this->where('common', $common)
                        ->map(function ($item) {
                            return [
                                'label' => $item->name,
                                'value' => $item->id
                            ];
                        });
        });

        return [
            'items' => [
                'common' => $this->collection->prepareTags(true),
                'secondary' => $this->collection->prepareTags(false)
            ],
            'count' => $this->count()
        ];
    }
}
