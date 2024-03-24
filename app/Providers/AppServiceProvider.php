<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Europe/Moscow');

        JsonResource::withoutWrapping();

        Relation::enforceMorphMap([
            'artist' => 'App\Models\Music\Artist',
            'album' => 'App\Models\Music\Album',
            'track' => 'App\Models\Music\Track',
            'task' => 'App\Models\Tasks\Task',
            'user' => 'App\Models\User',
        ]);
    }
}
