<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskListResource extends JsonResource
{
    public static $wrap = 'lists';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'createdAt' => $this->created_at,
            'items' => $this->tasks,
        ];
    }
}
