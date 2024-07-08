<?php

namespace App\Providers;

use App\Models\Music\AlbumType;
use App\Models\Music\MusicTag;
use App\Observers\AlbumTypeObserver;
use App\Observers\TagObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        MusicTag::observe(TagObserver::class);
        AlbumType::observe(AlbumTypeObserver::class);

        // Initialize cache if not exists
        if (!Cache::has('album_types')) {
            Cache::put('album_types', AlbumType::all(), now()->addDay());
        }
    }
}
