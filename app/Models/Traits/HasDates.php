<?php

namespace App\Models\Traits;

use Illuminate\Support\Carbon;

trait HasDates
{
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->format('Y-m-d H:i:s');
    }
}
