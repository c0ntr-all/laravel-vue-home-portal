<?php

namespace App\Http\Resources\Finances;

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
            'doneAt' => $this->done_at,
            'shop' => [
                'image' => $this->shop->image,
                'value' => $this->shop->value,
            ],
            'author' => [
                'username' => $this->user->name,
                'image' => $this->user->email,
            ]
        ];
    }
}
