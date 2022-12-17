<?php

namespace App\Http\Resources\Music\Artists;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    public static $wrap = 'artists';

    public function toArray($request): array
    {
        $commonTags = $this->tags->where('common', true);
        $secondaryTags = $this->tags->where('common', false);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'image' => $this->full_image,
            'createdAt' => $this->created_at,
            'albums' => $this->albums,
            'tags' => [
                'common' => $commonTags->pluck('id'),
                'secondary' => $secondaryTags->pluck('id')
            ],
            'tagsNames' => [
                'common' => $commonTags->pluck('name'),
                'secondary' => $secondaryTags->pluck('name')
            ]
        ];
    }
}
