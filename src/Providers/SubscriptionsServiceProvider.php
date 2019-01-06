<?php

declare(strict_types=1);

namespace Namdeveloper\Subscriptions\Providers;

use Namdeveloper\Subscriptions\Models\Plan;
use Illuminate\Support\ServiceProvider;
use Namdeveloper\Subscriptions\Models\PlanFeature;
use Namdeveloper\Subscriptions\Models\PlanSubscription;
use Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage;
use Namdeveloper\Subscriptions\Console\Commands\MigrateCommand;
use Namdeveloper\Subscriptions\Console\Commands\PublishCommand;
use Namdeveloper\Subscriptions\Console\Commands\RollbackCommand;

class SubscriptionsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.namdeveloper.subscriptions.migrate',
        PublishCommand::class => 'command.namdeveloper.subscriptions.publish',
        RollbackCommand::class => 'command.namdeveloper.subscriptions.rollback',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'namdeveloper.subscriptions');

        // Bind eloquent models to IoC container
        $this->app->singleton('namdeveloper.subscriptions.plan', $planModel = $this->app['config']['namdeveloper.subscriptions.models.plan']);
        $planModel === Plan::class || $this->app->alias('namdeveloper.subscriptions.plan', Plan::class);

        $this->app->singleton('namdeveloper.subscriptions.plan_features', $planFeatureModel = $this->app['config']['namdeveloper.subscriptions.models.plan_feature']);
        $planFeatureModel === PlanFeature::class || $this->app->alias('namdeveloper.subscriptions.plan_features', PlanFeature::class);

        $this->app->singleton('namdeveloper.subscriptions.plan_subscriptions', $planSubscriptionModel = $this->app['config']['namdeveloper.subscriptions.models.plan_subscription']);
        $planSubscriptionModel === PlanSubscription::class || $this->app->alias('namdeveloper.subscriptions.plan_subscriptions', PlanSubscription::class);

        $this->app->singleton('namdeveloper.subscriptions.plan_subscription_usage', $planSubscriptionUsageModel = $this->app['config']['namdeveloper.subscriptions.models.plan_subscription_usage']);
        $planSubscriptionUsageModel === PlanSubscriptionUsage::class || $this->app->alias('namdeveloper.subscriptions.plan_subscription_usage', PlanSubscriptionUsage::class);

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
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('namdeveloper.subscriptions.php')], 'namdeveloper-subscriptions-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'namdeveloper-subscriptions-migrations');
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
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }
}
