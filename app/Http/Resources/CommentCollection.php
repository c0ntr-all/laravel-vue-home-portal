<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'comments' => $this->collection,
            'commentsCount' => $this->count()
        ];
    }
}
