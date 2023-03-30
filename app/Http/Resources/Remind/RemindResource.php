<?php

namespace App\Http\Resources\Remind;

use Illuminate\Http\Resources\Json\JsonResource;

class RemindResource extends JsonResource
{
    public static $wrap = 'reminds';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'group' => $this->groupName,
            'time_left' => $this->time_left,
            'datetime' => $this->datetime,
            'is_active' => (bool)$this->is_active
        ];
    }
}
