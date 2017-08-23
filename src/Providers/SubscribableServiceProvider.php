<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Subscribable\Contracts\PlanContract;
use Rinvex\Subscribable\Contracts\PlanFeatureContract;
use Rinvex\Subscribable\Console\Commands\MigrateCommand;
use Rinvex\Subscribable\Contracts\PlanSubscriptionContract;
use Rinvex\Subscribable\Contracts\PlanSubscriptionUsageContract;

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

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.subscribable.plan', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan']();
        });
        $this->app->alias('rinvex.subscribable.plan', PlanContract::class);

        $this->app->singleton('rinvex.subscribable.plan_features', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan_feature']();
        });
        $this->app->alias('rinvex.subscribable.plan_features', PlanFeatureContract::class);

        $this->app->singleton('rinvex.subscribable.plan_subscriptions', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan_subscription']();
        });
        $this->app->alias('rinvex.subscribable.plan_subscriptions', PlanSubscriptionContract::class);

        $this->app->singleton('rinvex.subscribable.plan_subscription_usage', function ($app) {
            return new $app['config']['rinvex.subscribable.models.plan_subscription_usage']();
        });
        $this->app->alias('rinvex.subscribable.plan_subscription_usage', PlanSubscriptionUsageContract::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load migrations
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
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

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }
}
