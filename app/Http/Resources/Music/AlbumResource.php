<?php

namespace App\Http\Resources\Music;

use App\Http\Resources\Music\Tracks\TrackResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AlbumResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request): array
    {
        $artist = $this->artists->first();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'year' => $this->year,
            'content' => $this->content,
            'image' => $this->full_image,
            'createdAt' => $this->created_at,
            //todo: Сделать массив исполнителей, когда сплит или фит или еще чего
            'artist' => [
                'id' => $artist->id,
                'name' => $artist->name
            ],
            'tracks' => TrackResource::collection($this->tracks),
            'tags' => $this->tags->pluck('name')
        ];
    }
}
