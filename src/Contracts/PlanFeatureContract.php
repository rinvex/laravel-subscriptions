<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Contracts;

/**
 * Rinvex\Subscriptions\Contracts\PlanFeatureContract.
 *
 * @property int                                                                                               $id
 * @property int                                                                                               $plan_id
 * @property string                                                                                            $slug
 * @property array                                                                                             $name
 * @property array                                                                                             $description
 * @property string                                                                                            $value
 * @property int                                                                                               $resettable_period
 * @property string                                                                                            $resettable_interval
 * @property int                                                                                               $sort_order
 * @property \Carbon\Carbon                                                                                    $created_at
 * @property \Carbon\Carbon                                                                                    $updated_at
 * @property \Carbon\Carbon                                                                                    $deleted_at
 * @property-read \Rinvex\Subscriptions\Models\Plan                                                             $plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage[] $usage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature byPlanId($planId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereResettableInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereResettablePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereValue($value)
 * @mixin \Eloquent
 */
interface PlanFeatureContract
{
    //
}
