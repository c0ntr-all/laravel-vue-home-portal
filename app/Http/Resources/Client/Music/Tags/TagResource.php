<?php

namespace App\Http\Resources\Client\Music\Tags;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->name,
            'slug' => $this->slug,
            'content' => $this->content,
            'common' => $this->common,
            'created_at' => $this->created_at,
        ];
    }
}
