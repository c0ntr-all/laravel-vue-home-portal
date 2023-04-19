<?php

namespace App\Models\Widgets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Widget extends Model
{
    protected $fillable = [
        'id',
        'name',
        'model',
        'description',
    ];

    public function placement(): HasOne
    {
        return $this->HasOne(WidgetPlacement::class);
    }
}
