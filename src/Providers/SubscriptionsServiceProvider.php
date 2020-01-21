<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Providers;

use Rinvex\Subscriptions\Models\Plan;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Subscriptions\Models\PlanFeature;
use Rinvex\Subscriptions\Models\PlanSubscription;
use Rinvex\Subscriptions\Models\PlanSubscriptionUsage;
use Rinvex\Subscriptions\Console\Commands\MigrateCommand;
use Rinvex\Subscriptions\Console\Commands\PublishCommand;
use Rinvex\Subscriptions\Console\Commands\RollbackCommand;

class SubscriptionsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

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
        $this->app->singleton('rinvex.subscriptions.plan', $planModel = $this->app['config']['rinvex.subscriptions.models.plan']);
        $planModel === Plan::class || $this->app->alias('rinvex.subscriptions.plan', Plan::class);

        $this->app->singleton('rinvex.subscriptions.plan_feature', $planFeatureModel = $this->app['config']['rinvex.subscriptions.models.plan_feature']);
        $planFeatureModel === PlanFeature::class || $this->app->alias('rinvex.subscriptions.plan_feature', PlanFeature::class);

        $this->app->singleton('rinvex.subscriptions.plan_subscription', $planSubscriptionModel = $this->app['config']['rinvex.subscriptions.models.plan_subscription']);
        $planSubscriptionModel === PlanSubscription::class || $this->app->alias('rinvex.subscriptions.plan_subscription', PlanSubscription::class);

        $this->app->singleton('rinvex.subscriptions.plan_subscription_usage', $planSubscriptionUsageModel = $this->app['config']['rinvex.subscriptions.models.plan_subscription_usage']);
        $planSubscriptionUsageModel === PlanSubscriptionUsage::class || $this->app->alias('rinvex.subscriptions.plan_subscription_usage', PlanSubscriptionUsage::class);

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
        // Publish Resources
        $this->publishesConfig('rinvex/laravel-subscriptions');
        $this->publishesMigrations('rinvex/laravel-subscriptions');
        ! $this->autoloadMigrations('rinvex.subscriptions') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
