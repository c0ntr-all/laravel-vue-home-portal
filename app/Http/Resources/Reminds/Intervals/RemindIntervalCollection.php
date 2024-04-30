<?php

namespace App\Http\Resources\Reminds\Intervals;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RemindIntervalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'count' => $this->count(),
            'items' => $this->collection
        ];
    }
}
