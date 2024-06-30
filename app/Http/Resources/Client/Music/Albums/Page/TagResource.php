<?php

namespace App\Http\Resources\Client\Music\Albums\Page;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'group' => $this->tagables->group ? [
                'id' => $this->tagables->group->id,
                'name' => $this->tagables->group->name
            ] : null
        ];
    }
}
