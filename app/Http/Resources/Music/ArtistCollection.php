<?php

namespace App\Http\Resources\Music;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'artists' => $this->collection,
            'artistsCount' => $this->count()
        ];
    }
}
