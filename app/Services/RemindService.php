<?php

namespace App\Services;

use App\Models\Reminds\Remind;
use App\Models\Reminds\RemindGroup;

class RemindService
{
    protected $remindGroup;

    public function __construct(RemindGroup $remindGroup)
    {
        $this->remindGroup = $remindGroup;
    }

    public function syncGroup(Remind $remind, string $group): void
    {
        $groupId = $this->remindGroup->firstOrCreate([
            'name' => $group,
            'user_id' => auth()->user()->id,
            'color' => $group
        ])->id;

        $remind->group()->associate($groupId);
        $remind->save();
    }
}
