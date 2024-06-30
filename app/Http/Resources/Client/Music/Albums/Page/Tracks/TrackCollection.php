<?php

namespace App\Http\Resources\Client\Music\Albums\Page\Tracks;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TrackCollection extends ResourceCollection
{
    public static $wrap = 'data';

    public function withResponse($request, $response): void
    {
        $arrResponse = json_decode($response->getContent(), true);

        unset($arrResponse['links'], $arrResponse['meta']);

        $response->setContent(json_encode($arrResponse['data']));
    }

    public function toArray($request): array
    {
        return [
            'success' => true,
            'tracks' => $this->collection,
            'count' => $this->count(),
            'pagination' => [
                'perPage' => $this->perPage(),
                'nextPageUrl' => $this->nextPageUrl(),
                'prevPageUrl' => $this->previousPageUrl(),
                'hasPages' => $this->hasMorePages()
            ]
        ];
    }
}
