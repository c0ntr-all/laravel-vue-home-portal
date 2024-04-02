<?php

namespace App\Http\Resources\Reminds;

use App\Http\Resources\RemindGroups\RemindGroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RemindResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'time_left' => $this->time_left,
            'datetime' => $this->datetime->format('Y-m-d H:i'),
            'group' => new RemindGroupResource($this->whenLoaded('group')),
            'is_active' => (bool)$this->is_active
        ];
    }
}
