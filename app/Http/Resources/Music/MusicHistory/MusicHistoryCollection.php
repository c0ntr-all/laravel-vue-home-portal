<?php

namespace App\Http\Resources\Music\MusicHistory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MusicHistoryCollection extends ResourceCollection
{
    public static $wrap = '';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'total' => $this->count(),
            'items' => $this->collection
        ];
    }
}
