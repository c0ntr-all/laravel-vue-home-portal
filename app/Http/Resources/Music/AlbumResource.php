<?php

namespace App\Http\Resources\Music;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AlbumResource extends JsonResource
{
    public static $wrap = '';

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
            'artist' => ['id' => $this->artist->id, 'name' => $this->artist->name],
            'tracks' => $this->tracks()->get(['id', 'number', 'name', 'duration']),
            'tags' => $this->tags->pluck('tag')
        ];
    }
}
