<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Contracts;

/**
 * Rinvex\Subscriptions\Contracts\PlanSubscriptionContract.
 *
 * @property int                                                                                               $id
 * @property int                                                                                               $user_id
 * @property int                                                                                               $plan_id
 * @property string                                                                                            $slug
 * @property array                                                                                             $name
 * @property array                                                                                             $description
 * @property \Carbon\Carbon                                                                                    $trial_ends_at
 * @property \Carbon\Carbon                                                                                    $starts_at
 * @property \Carbon\Carbon                                                                                    $ends_at
 * @property \Carbon\Carbon                                                                                    $cancels_at
 * @property \Carbon\Carbon                                                                                    $canceled_at
 * @property \Carbon\Carbon                                                                                    $created_at
 * @property \Carbon\Carbon                                                                                    $updated_at
 * @property \Carbon\Carbon                                                                                    $deleted_at
 * @property-read \Rinvex\Subscriptions\Models\Plan                                                             $plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage[] $usage
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription byPlanId($planId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription byUserId($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription findEndedPeriod()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription findEndedTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription findEndingPeriod($dayRange = 3)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription findEndingTrial($dayRange = 3)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereCancelsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanSubscription whereUserId($value)
 * @mixin \Eloquent
 */
interface PlanSubscriptionContract
{
    //
}
