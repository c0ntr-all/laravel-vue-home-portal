<?php

namespace App\Models\Widgets;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WidgetPlacement extends Model
{
    public $table = 'widgets_placements';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
                'widget' => $widgetModel->getWidget()
            ];
        });
    }
}
