<?php

namespace App\Http\Resources\RemindGroups;

use Illuminate\Http\Resources\Json\JsonResource;

class RemindGroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
