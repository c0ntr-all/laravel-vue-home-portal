<?php declare(strict_types=1);

namespace App\Repositories\Reminds;

use App\Models\Reminds\RemindGroup;

class RemindGroupRepository
{
    /**
     * @return mixed
     */
    public function getList(): mixed
    {
        $select = [
            'id',
            'name',
        ];

        return RemindGroup::select($select)
                     ->orderByDesc('created_at')
                     ->get();
    }
}
