<?php

namespace App\Observers;

use App\Models\Music\AlbumVersionType;
use Illuminate\Support\Facades\Cache;

class AlbumVersionTypeObserver
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
        Cache::put('album_version_types', AlbumVersionType::all(), now()->addDay());
    }
}
