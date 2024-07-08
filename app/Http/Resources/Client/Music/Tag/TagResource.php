<?php

namespace App\Http\Resources\Client\Music\Tag;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_base' => $this->is_base,
            'createdAt' => $this->created_at,
        ];
    }
}
