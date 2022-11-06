<?php

namespace App\Http\Resources\Music;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistCollection extends ResourceCollection
{
    public static $wrap = 'data';

    public function withResponse($request, $response)
    {
        $arrResponse = json_decode($response->getContent(), true);

        unset($arrResponse['links'], $arrResponse['meta']);

        $response->setContent(json_encode($arrResponse['data']));
    }

    public function toArray($request): array
    {
        return [
            'artists' => $this->collection,
            'count' => $this->count(),
            'pagination' => [
                'per_page' => $this->perPage(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
            ]
        ];
    }
}
