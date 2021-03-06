<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemindResource extends JsonResource
{
    public static $wrap = 'reminds';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'datetime' => $this->datetime,
            'isActive' => (bool)$this->is_active,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
