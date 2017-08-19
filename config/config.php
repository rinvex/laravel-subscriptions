<?php

declare(strict_types=1);

return [

    // Subscribable Database Tables
    'tables' => [

        'plans' => 'plans',
        'plan_features' => 'plan_features',
        'plan_subscriptions' => 'plan_subscriptions',
        'plan_subscription_usage' => 'plan_subscription_usage',

    ],

    // Subscribable Models
    'models' => [

        'plan' => \Rinvex\Subscribable\Models\Plan::class,
        'plan_feature' => \Rinvex\Subscribable\Models\PlanFeature::class,
        'plan_subscription' => \Rinvex\Subscribable\Models\PlanSubscription::class,
        'plan_subscription_usage' => \Rinvex\Subscribable\Models\PlanSubscriptionUsage::class,

    ],

];
