<?php

namespace App\Http\Resources\Admin\Music\Artists;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    public static $wrap = 'artists';

    public function toArray($request): array
    {
        // С помощью collect($collection->values()) сбрасываем ключи коллекции т.к. для secondary ключи идут не с 0.
        // Это приводит к формированию объекта, а не массива, для фронта.
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'image' => $this->full_image,
            'createdAt' => $this->created_at,
            'albums' => $this->albums,
            'tags' => [
                'common' => collect($this->tags->where('common', true)->map(function($item) {
                    return ['label' => $item->name, 'value' => $item->id];
                })->values()),
                'secondary' => collect($this->tags->where('common', false)->map(function($item) {
                    return ['label' => $item->name, 'value' => $item->id];
                })->values()),
            ]
        ];
    }
}
