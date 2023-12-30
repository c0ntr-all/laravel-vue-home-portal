<?php

namespace App\Models\Widgets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Widgets\Widget
 *
 * @property int $id
 * @property string $name
 * @property string $model
 * @property string $description
 * @property-read \App\Models\Widgets\WidgetPlacement|null $placement
 * @method static \Illuminate\Database\Eloquent\Builder|Widget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Widget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Widget query()
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereName($value)
 * @mixin \Eloquent
 */
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
