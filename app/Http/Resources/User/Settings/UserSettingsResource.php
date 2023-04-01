<?php

namespace App\Http\Resources\User\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingsResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return [
            'key' => $this->key,
            'name' => $this->key,
            'value' => json_decode($this->value)
        ];
    }
}
