<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public static $wrap = 'tags';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->name,
            'type' => '',
            'createdAt' => $this->created_at,
        ];
    }
}
