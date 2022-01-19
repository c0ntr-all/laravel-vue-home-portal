<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TaskResource;

class TaskListResource extends JsonResource
{
    public static $wrap = 'lists';

    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'createdAt' => $this->created_at,
            'items' => $this->items,
        ];
    }
}
