<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskItemsResource extends JsonResource
{
    public static $wrap = 'items';

    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->created_at,
        ];
    }
}
