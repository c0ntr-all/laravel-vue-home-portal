<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public static $wrap = 'items';

    public function toArray($request): array
    {
        $this->setUserNameToComments();

        return [
            'id' => $this->id,
            'list_id' => $this->list_id,
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->created_at,
            'comments' => $this->comments
        ];
    }

    private function setUserNameToComments()
    {
        //Тупая запись, но аксессор работает только при обращении к нему. Другого варианта пока не придумал
        foreach($this->comments as &$comment) {
            $comment->user_name = $comment->user_name;
        }

        return $this->comments;
    }
}
