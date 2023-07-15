<?php

namespace App\Http\Resources\Music\Tracks;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'duration' => $this->duration,
            'rate' => $this->rate?->rate ?? 0,
            'artist' => $this->album->artist->name,
            'image' => $this->full_image,
        ];

        if ($request->input('with_tags')) {
            $data['tags'] = [
                'common' => $this->tags->where('common', true)->pluck('name'),
                'secondary' => $this->tags->where('common', false)->pluck('name')
            ];
        }

        return $data;
    }
}
