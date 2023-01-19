<?php

namespace App\Http\Resources\Music\Artists;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminArtistResource extends JsonResource
{
    public static $wrap = 'artists';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'image' => $this->full_image,
            'createdAt' => $this->created_at,
            'albums' => $this->albums,
            'tags' => [
                'common' => $this->tags->where('common', true)->map(function($item) {
                    return ['label' => $item->name, 'value' => $item->id];
                }),
                'secondary' => $this->tags->where('common', false)->map(function($item) {
                    return ['label' => $item->name, 'value' => $item->id];
                }),
            ]
        ];
    }
}
