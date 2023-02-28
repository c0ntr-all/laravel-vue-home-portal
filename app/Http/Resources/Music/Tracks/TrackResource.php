<?php

namespace App\Http\Resources\Music\Tracks;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'duration' => $this->duration,
            'rate' => $this->rate?->rate ?? 0,
            'image' => $this->full_image,
            'artist' => $this->album->artist->name,
        ];
    }
}
