<?php

namespace App\Http\Resources\Music\Artists;

use App\Http\Resources\Music\Tracks\TrackResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AlbumResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $this->year,
            'image' => $this->full_image,
        ];
    }
}
