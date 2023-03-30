<?php

namespace App\Http\Resources\Remind;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RemindCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'reminds' => $this->collection,
            'remindsCount' => $this->count()
        ];
    }
}
