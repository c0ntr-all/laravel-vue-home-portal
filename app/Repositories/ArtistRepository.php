<?php

namespace App\Repositories;

use App\Filters\Filter;
use App\Models\Music\Artist;

class ArtistRepository
{
    public static function getWithCursor(Filter|null $filter)
    {
        return Artist::filter($filter)
                     ->orderByDesc('created_at')
                     ->cursorPaginate(12);
    }

    public static function getWithPaginate(Filter|null $filter)
    {
        return Artist::filter($filter)
                     ->orderByDesc('created_at')
                     ->paginate(100);
    }
}
