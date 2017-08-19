<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Subscribable\Console\Commands\MigrateCommand;

class SubscribableServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.subscribable.migrate',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.subscribable');

        // Register eloquent models
        $this->app->singleton('rinvex.subscribable.plan', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan']();
        });

        $this->app->singleton('rinvex.subscribable.plan_features', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan_features']();
        });

        $this->app->singleton('rinvex.subscribable.plan_subscriptions', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan_subscriptions']();
        });

        $this->app->singleton('rinvex.subscribable.plan_subscription_usage', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan_subscription_usage']();
        });

        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
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
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.subscribable.php')], 'rinvex-subscribable-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-subscribable-migrations');
    }
}
