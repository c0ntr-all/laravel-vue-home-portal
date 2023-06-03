<?php

namespace App\Http\Resources\Music\MusicHistory;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->track->id,
            'number' => $this->track->number,
            'name' => $this->track->name,
            'duration' => $this->track->duration,
            'rate' => $this->track->rate?->rate ?? 0,
            'artist' => $this->track->album->artist->name,
            'image' => $this->track->full_image,
            'tags' => [
                'common' => $this->track->tags->where('common', true)->pluck('name'),
                'secondary' => $this->track->tags->where('common', false)->pluck('name')
            ],
            'listen_date' => $this->updated_at
        ];
    }
}
