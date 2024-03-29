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
            'label' => $this->name,
            'slug' => $this->slug,
            'content' => $this->content,
            'common' => $this->common,
            'createdAt' => $this->created_at,
        ];
    }
}
