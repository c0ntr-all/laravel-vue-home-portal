<?php

namespace App\Http\Resources\Reminds;

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
            'is_active' => (bool)$this->is_active
        ];
    }
}
