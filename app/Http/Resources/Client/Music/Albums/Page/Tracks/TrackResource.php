<?php

namespace App\Http\Resources\Client\Music\Albums\Page\Tracks;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'duration' => $this->duration,
            'link' => $this->link,
            'rate' => $this->rate?->rate ?? 0,
            'artist' => $this->artists?->first()?->name,
            'image' => $this->full_image,
        ];
    }
}
