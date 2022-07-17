<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Tag;
use App\Observers\TagObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'artist' => 'App\Models\Music\Artist',
            'album' => 'App\Models\Music\Album',
            'track' => 'App\Models\Music\Track',
            'task' => 'App\Models\Tasks\Task',
        ]);

        Tag::observe(TagObserver::class);
    }
}
