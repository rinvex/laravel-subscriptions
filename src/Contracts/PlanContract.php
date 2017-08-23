<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Contracts;

/**
 * Rinvex\Subscribable\Contracts\PlanContract.
 *
 * @property int                                                                                          $id
 * @property string                                                                                       $slug
 * @property array                                                                                        $name
 * @property array                                                                                        $description
 * @property bool                                                                                         $is_active
 * @property float                                                                                        $price
 * @property float                                                                                        $signup_fee
 * @property string                                                                                       $currency
 * @property int                                                                                          $trial_period
 * @property string                                                                                       $trial_interval
 * @property int                                                                                          $invoice_period
 * @property string                                                                                       $invoice_interval
 * @property int                                                                                          $grace_period
 * @property string                                                                                       $grace_interval
 * @property int                                                                                          $prorate_day
 * @property int                                                                                          $prorate_period
 * @property int                                                                                          $prorate_extend_due
 * @property int                                                                                          $active_subscribers_limit
 * @property int                                                                                          $sort_order
 * @property \Carbon\Carbon                                                                               $created_at
 * @property \Carbon\Carbon                                                                               $updated_at
 * @property \Carbon\Carbon                                                                               $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscribable\Models\PlanFeature[]      $features
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscribable\Models\PlanSubscription[] $subscriptions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan active()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereActiveSubscribersLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereGraceInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereGracePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereInvoiceInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereInvoicePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereProrateDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereProrateExtendDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereProratePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereSignupFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereTrialInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereTrialPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscribable\Models\Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface PlanContract
{
    //
}
