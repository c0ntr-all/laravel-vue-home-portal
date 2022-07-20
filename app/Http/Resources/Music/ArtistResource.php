<?php

namespace App\Http\Resources\Music;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
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
            'tags' => $this->tags->pluck('name')
        ];
    }
}
