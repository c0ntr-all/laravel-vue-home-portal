<?php

namespace App\Http\Resources\Music\Tag;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public static $wrap = '';

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
