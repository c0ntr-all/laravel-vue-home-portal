<?php

namespace App\Http\Resources\Admin\Music\Tag;

use Illuminate\Http\Resources\Json\JsonResource;

class TagTreeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'content' => $this->content,
            'common' => $this->common,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'children' => $this->children
        ];
    }
}
