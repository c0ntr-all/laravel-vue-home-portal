<?php

namespace App\Http\Resources\Admin\Music\Artists;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $lastPageNum = ceil($this->total() / $this->perPage());

        return [
            'artists' => $this->collection,
            'total' => $this->total(),
            'pagination' => [
                'first_page_url' => $this->url(1),
                'last_page' => $lastPageNum,
                'last_page_url' => $this->url($lastPageNum),
                'next_page_url' => $this->nextPageUrl(),
                'per_page' => $this->perPage(),
                'prev_page_url' => $this->previousPageUrl(),
            ]
        ];
    }
}
