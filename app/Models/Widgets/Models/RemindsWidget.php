<?php

namespace App\Models\Widgets\Models;

use App\Models\Reminds\Remind;
use App\Models\Widgets\Widget;
use Illuminate\Support\Carbon;

class RemindsWidget extends Widget implements WidgetInterface
{
    public function getWidget(): array
    {
        $allReminds = Remind::all();

        $remindsCount = $allReminds->count();
        $activeRemindsCount = $allReminds->where('is_active', 1)
                                         ->count();
        $nearestReminds = $allReminds->whereBetween('datetime', $this->createRange())
                                     ->where('is_active', 1)
                                     ->sortByDesc('datetime')
                                     /* To use array instead of object in output */
                                     ->values()
                                     ->toArray();

        return [
            'remindsCount' => $remindsCount,
            'activeRemindsCount' => $activeRemindsCount,
            'reminds' => $nearestReminds
        ];
    }

    private function createRange(): array
    {
        $now = (new Carbon())->now();

        $firstDate = $now->format('Y-m-d H:i:s');
        $lastDate = $now->addDays(3)->format('Y-m-d H:i:s');

        return [
            $firstDate,
            $lastDate
        ];
    }
}
