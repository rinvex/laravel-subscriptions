<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Subscriptions\Contracts\PlanContract;
use Rinvex\Subscriptions\Contracts\PlanFeatureContract;
use Rinvex\Subscriptions\Console\Commands\MigrateCommand;
use Rinvex\Subscriptions\Console\Commands\PublishCommand;
use Rinvex\Subscriptions\Console\Commands\RollbackCommand;
use Rinvex\Subscriptions\Contracts\PlanSubscriptionContract;
use Rinvex\Subscriptions\Contracts\PlanSubscriptionUsageContract;

class SubscriptionsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.subscriptions.migrate',
        PublishCommand::class => 'command.rinvex.subscriptions.publish',
        RollbackCommand::class => 'command.rinvex.subscriptions.rollback',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.subscriptions');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.subscriptions.plan', function ($app) {
            return new $app['config']['rinvex.subscriptions.models.plan']();
        });
        $this->app->alias('rinvex.subscriptions.plan', PlanContract::class);

        $this->app->singleton('rinvex.subscriptions.plan_features', function ($app) {
            return new $app['config']['rinvex.subscriptions.models.plan_feature']();
        });
        $this->app->alias('rinvex.subscriptions.plan_features', PlanFeatureContract::class);

        $this->app->singleton('rinvex.subscriptions.plan_subscriptions', function ($app) {
            return new $app['config']['rinvex.subscriptions.models.plan_subscription']();
        });
        $this->app->alias('rinvex.subscriptions.plan_subscriptions', PlanSubscriptionContract::class);

        $this->app->singleton('rinvex.subscriptions.plan_subscription_usage', function ($app) {
            return new $app['config']['rinvex.subscriptions.models.plan_subscription_usage']();
        });
        $this->app->alias('rinvex.subscriptions.plan_subscription_usage', PlanSubscriptionUsageContract::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
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
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.subscriptions.php')], 'rinvex-subscriptions-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-subscriptions-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
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
