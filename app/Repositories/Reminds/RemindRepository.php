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
            'reminds.id',
            'reminds.title',
            'reminds.content',
            'reminds.group_id',
            'reminds.datetime',
            'reminds.is_active',
            'reminds.is_regular',
            'reminds.interval',
            'reminds.to_remind_before',
            'reminds_groups.id as g_id',
            'reminds_groups.sort',
        ];

        return Remind::leftJoin('reminds_groups', 'reminds.group_id', '=', 'reminds_groups.id')
                     ->with(['group'])
                     ->select($select)
                     ->orderByRaw('CASE WHEN reminds_groups.sort IS NOT NULL THEN 0 ELSE 1 END ASC')
                     ->orderBy('reminds_groups.sort')
                     ->orderByRaw('CASE WHEN reminds_groups.sort IS NULL THEN reminds_groups.id END DESC')
                     ->get();
    }
}
