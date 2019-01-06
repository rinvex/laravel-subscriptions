<?php

declare(strict_types=1);

return [

    // Subscriptions Database Tables
    'tables' => [

        'plans' => 'plans',
        'plan_features' => 'plan_features',
        'plan_subscriptions' => 'plan_subscriptions',
        'plan_subscription_usage' => 'plan_subscription_usage',

    ],

    // Subscriptions Models
    'models' => [

        'plan' => \Namdeveloper\Subscriptions\Models\Plan::class,
        'plan_feature' => \Namdeveloper\Subscriptions\Models\PlanFeature::class,
        'plan_subscription' => \Namdeveloper\Subscriptions\Models\PlanSubscription::class,
        'plan_subscription_usage' => \Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage::class,

    ],

];
