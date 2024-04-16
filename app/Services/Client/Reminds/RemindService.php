<?php

namespace App\Services\Client\Reminds;

use App\Models\Reminds\Remind;

class RemindService
{
    public function __construct()
    {
    }

    /**
     * @param array $data
     * @return Remind
     */
    public function createRemind(array $data): Remind
    {
        return Remind::create(array_merge($data, [
            'user_id' => auth()->id(),
            'group_id' => $data['group_id'] ?? null
        ]));
    }

    /**
     * @param Remind $remind
     * @param array $data
     * @return Remind
     */
    public function updateRemind(Remind $remind, array $data): Remind
    {
        $remind->update(array_merge($data, [
            'user_id' => auth()->id(),
            'group_id' => $data['group_id'] ?? null
        ]));

        return $remind;
    }
}
