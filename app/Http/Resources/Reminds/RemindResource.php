<?php

namespace App\Http\Resources\Reminds;

use App\Enums\Reminds\RemindIntervalEnum;
use App\Http\Resources\RemindGroups\RemindGroupResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class RemindResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'time_left' => $this->time_left,
            'datetime' => $this->datetime->format('Y-m-d H:i'),
            'group' => new RemindGroupResource($this->whenLoaded('group')),
            'is_regular' => (bool)$this->is_regular,
            'interval' => $this->when($this->is_regular, function () {
                return [
                    'label' => RemindIntervalEnum::getLabeL($this->interval),
                    'value' => $this->interval,
                ];
            }),
            'to_remind_before' => (bool)$this->to_remind_before,
            'is_active' => (bool)$this->is_active
        ];
    }
}
