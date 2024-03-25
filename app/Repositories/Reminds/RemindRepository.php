<?php

namespace App\Repositories\Reminds;

use App\Models\Reminds\Remind;

class RemindRepository
{
    /**
     * @return mixed
     */
    public function getList(): mixed
    {
        $select = [
            'id',
            'title',
            'content',
            'group_id',
            'datetime',
            'is_active'
        ];

        return Remind::select($select)
                     ->orderByDesc('group_id')
                     ->orderByDesc('is_active')
                     ->orderByDesc('datetime')
                     ->get();
    }

    public function store($requestData)
    {
        return auth()->user()->playlists()->create($requestData);
    }
}
