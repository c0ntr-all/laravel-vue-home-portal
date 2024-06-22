<?php

namespace App\Observers;

use App\Models\Music\AlbumType;
use Illuminate\Support\Facades\Cache;

class AlbumTypeObserver
{
    public function saved(): void
    {
        $this->updateCache();
    }

    public function deleted(): void
    {
        $this->updateCache();
    }

    /**
     * Update the cache for album version types.
     *
     * @return void
     */
    protected function updateCache(): void
    {
        Cache::put('album_types', AlbumType::all(), now()->addDay());
    }
}
