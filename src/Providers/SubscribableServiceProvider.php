<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Providers;

use Illuminate\Support\ServiceProvider;

class SubscribableServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.subscribable');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Load migrations
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

            // Publish Resources
            $this->publishResources();
        }
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'migrations');
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.subscribable.php')], 'config');
    }
}
