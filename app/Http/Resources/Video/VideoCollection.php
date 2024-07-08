<?php

namespace App\Http\Resources\Video;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VideoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'count' => $this->collection->count(),
            'items' => $this->collection->map(function($item, $key) {
                return [
                    'key' => $key,
                    'name' => basename($item),
                    'path' => $item
                ];
            })
        ];
    }
}
