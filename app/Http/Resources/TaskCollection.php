<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'tasks' => $this->collection,
            'tasksCount' => $this->count()
        ];
    }
}
