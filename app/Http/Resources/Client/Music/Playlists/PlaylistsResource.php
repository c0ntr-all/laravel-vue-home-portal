<?php

namespace App\Http\Resources\Client\Music\Playlists;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at
        ];

        if ($request->input('with_tracks')) {
            $data['tracks'] = $this->tracks->pluck('id');
        }

        return $data;
    }
}
