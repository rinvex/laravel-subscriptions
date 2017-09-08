<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Contracts;

/**
 * Rinvex\Subscriptions\Contracts\PlanSubscriptionUsageContract.
 *
 * @property int                                               $id
 * @property int                                               $subscription_id
 * @property int                                               $feature_id
 * @property int                                               $used
 * @property \Carbon\Carbon                                    $valid_until
 * @property \Carbon\Carbon                                    $created_at
 * @property \Carbon\Carbon                                    $updated_at
 * @property \Carbon\Carbon                                    $deleted_at
 * @property-read \Rinvex\Subscriptions\Models\PlanFeature      $feature
 * @property-read \Rinvex\Subscriptions\Models\PlanSubscription $subscription
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage byFeatureSlug($featureSlug)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereFeatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage whereValidUntil($value)
 * @mixin \Eloquent
 */
interface PlanSubscriptionUsageContract
{
    //
}
