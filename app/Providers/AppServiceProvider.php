<?php

namespace App\Providers;

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
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
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

        Relation::enforceMorphMap([
            'artist' => 'App\Models\Music\Artist',
            'album' => 'App\Models\Music\Album',
            'track' => 'App\Models\Music\Track',
            'task' => 'App\Models\Tasks\Task',
        ]);
    }
}
