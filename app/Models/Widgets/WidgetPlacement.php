<?php

namespace App\Models\Widgets;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\HasUser;

/**
 * App\Models\Widgets\WidgetPlacement
 *
 * @property int $id
 * @property int $user_id
 * @property int $widget_id
 * @property int $rank
 * @property string $size
 * @property-read User $user
 * @property-read \App\Models\Widgets\Widget $widget
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement query()
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement whereUser(string $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WidgetPlacement whereWidgetId($value)
 * @mixin \Eloquent
 */
class WidgetPlacement extends Model
{
    use HasUser;

    public $table = 'widgets_placements';

    public function widget(): belongsTo
    {
        return $this->belongsTo(Widget::class);
    }

    public function getUserWidgetsList(): Collection
    {
        return User::find(auth()->user()->id)->first()->widgets()->get();
    }

    public function getAll()
    {
        $widgetsPlacementsList = $this->getUserWidgetsList();

        return $widgetsPlacementsList->map(function($item) {
            $widgetModel = new $item->widget->model;

            return [
                'name' => $item->widget->name,
                'type' => basename($item->widget->model),
                'data' => $widgetModel->getWidget()
            ];
        });
    }
}
