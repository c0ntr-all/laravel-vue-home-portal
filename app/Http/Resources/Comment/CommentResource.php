<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public static $wrap = 'comments';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'user_name' => $this->user_name,
        ];
    }
}
