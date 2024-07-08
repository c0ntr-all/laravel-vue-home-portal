<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskListCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'lists' => $this->collection,
            'listsCount' => $this->count()
        ];
    }
}
