<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancesResource extends JsonResource
{
    public static $wrap = 'finances';

    public function toArray($request): array
    {
        return [
            'type' => $this->type,
            'summ' => $this->summ,
            'comment' => $this->comment,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'shop' => [
                'image' => $this->shop->image,
                'value' => $this->shop->value,
            ],
            'author' => [
                'username' => $this->user->username,
                'image' => $this->user->image,
            ]
        ];
    }
}
