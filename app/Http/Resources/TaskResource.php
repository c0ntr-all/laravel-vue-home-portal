<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public static $wrap = 'items';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'list_id' => $this->list_id,
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->created_at,
            'comments' => $this->comments
        ];
    }
}
