<?php

namespace App\Http\Resources\Music;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AlbumResource extends JsonResource
{
    public static $wrap = 'albums';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'year' => $this->year,
            'content' => $this->content,
            'image' => $this->full_image,
            'createdAt' => $this->created_at,
            'artist' => $this->artist->pluck('name'),
            'tracks' => $this->getTracks(),
            'tags' => $this->tags->pluck('tag')
        ];
    }
}
