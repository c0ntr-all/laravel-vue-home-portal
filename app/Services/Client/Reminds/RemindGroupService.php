<?php

namespace App\Services\Client\Reminds;


use App\Models\Reminds\RemindGroup;

class RemindGroupService
{
    public function __construct()
    {
    }

    public function deleteRemindGroup(RemindGroup $remindGroup): ?bool
    {
        return $remindGroup->delete();
    }

    public function saveRemindGroup(array $data): RemindGroup
    {
        if (array_key_exists('id', $data)) {
            $remindGroup = RemindGroup::find($data['id']);
            unset($data['id']);

            $remindGroup->update($data);
        } else {
            $remindGroup = RemindGroup::create([
                'name' => $data['name'],
                'user_id' => auth()->user()->id,
                'color' => $data['color']
            ]);
        }

        return $remindGroup;
    }
}
