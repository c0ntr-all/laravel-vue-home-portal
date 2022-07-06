<?php

namespace App\Http\Resources\Music;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ArtistResource extends JsonResource
{
    public static $wrap = 'artists';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'content' => $this->content,
            'image' => $this->image,
            'fullImage' => $this->full_image,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
            'albums' => $this->albums,
            'tags' => $this->tags->pluck('tag')
        ];
    }
}
