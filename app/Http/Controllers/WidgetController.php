<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Widgets\WidgetPlacement;

class WidgetController extends Controller
{
    public WidgetPlacement $widgets;

    public function __construct(WidgetPlacement $widgets)
    {
        $this->widgets = $widgets;
    }
    public function getWidgets(): Collection
    {
        return $this->widgets->getAll();
    }
}
