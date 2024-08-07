<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'tasks' => $this->collection,
            'tasksCount' => $this->count()
        ];
    }
}
