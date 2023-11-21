<?php declare(strict_types=1);

namespace App\Repositories;

use App\Filters\Filter;
use App\Models\Music\Track;

class TrackRepository
{
    public function getWithCursor(Filter|null $filter)
    {
        return Track::filter($filter)
                     ->orderByDesc('created_at')
                     ->cursorPaginate(12);
    }

    public function getWithPaginate(Filter|null $filter)
    {
        return Track::filter($filter)
                     ->orderByDesc('created_at')
                     ->paginate(100);
    }
}
