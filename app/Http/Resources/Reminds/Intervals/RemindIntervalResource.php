<?php

namespace App\Http\Resources\Reminds\Intervals;

use App\Enums\Reminds\RemindIntervalEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RemindIntervalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'label' => RemindIntervalEnum::getLabeL($this->interval),
            'value' => $this->interval,
        ];
    }
}
