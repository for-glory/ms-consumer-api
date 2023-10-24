<?php

namespace App\Providers;

use App\Infrastructure\Search\ElasticSearch\Engine as ElasticEngine;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('elasticsearch', function () {
            return $this->app->make(ElasticEngine::class);
        });
    }
}
