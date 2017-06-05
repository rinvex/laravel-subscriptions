<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Models;

use DB;
use Carbon\Carbon;
use LogicException;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Subscribable\Services\Period;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Subscribable\Traits\BelongsToPlan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rinvex\Subscribable\Models\PlanSubscription
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
 * @property-read \Rinvex\Subscribable\Models\Plan                                                             $plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscribable\Models\PlanSubscriptionUsage[] $usage
 * @property-read \Cortex\Fort\Models\User                                                                     $user
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription byPlanId($planId)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription byUserId($userId)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription findEndedPeriod()
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription findEndedTrial()
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription findEndingPeriod($dayRange = 3)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription findEndingTrial($dayRange = 3)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereCanceledAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereCancelsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereEndsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription wherePlanId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereStartsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscription whereUserId($value)
 * @mixin \Eloquent
 */
class PlanSubscription extends Model
{
    use HasSlug;
    use BelongsToPlan;
    use HasTranslations;
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'slug',
        'name',
        'description',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancels_at',
        'canceled_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancels_at',
        'canceled_at',
        'deleted_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = ['validating', 'validated'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.subscribable.tables.plan_subscriptions'));
        $this->setRules([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'slug' => 'required|alpha_dash|unique:'.config('rinvex.subscribable.tables.plan_subscriptions').',slug',
            'plan_id' => 'required|integer|exists:'.config('rinvex.subscribable.tables.plans').',id',
            'user_id' => 'required|integer|exists:'.config('rinvex.fort.tables.users').',id',
            'trial_ends_at' => 'nullable|date',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'cancels_at' => 'nullable|date',
            'canceled_at' => 'nullable|date',
        ]);
    }

    /**
     * Boot function for using with User Events.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (isset(static::$dispatcher)) {
            // Early auto generate slugs before validation
            static::$dispatcher->listen('eloquent.validating: '.static::class, function (self $model) {
                if (! $model->starts_at || ! $model->ends_at) {
                    $model->setNewPeriod();
                }

                if (! $model->slug) {
                    if ($model->exists) {
                        $model->generateSlugOnUpdate();
                    } else {
                        $model->generateSlugOnCreate();
                    }
                }
            });
        }
    }

    /**
     * Set the translatable name attribute.
     *
     * @param string $value
     *
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = json_encode(! is_array($value) ? [app()->getLocale() => $value] : $value);
    }

    /**
     * Set the translatable description attribute.
     *
     * @param string $value
     *
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ! empty($value) ? json_encode(! is_array($value) ? [app()->getLocale() => $value] : $value) : null;
    }

    /**
     * Enforce clean slugs.
     *
     * @param string $value
     *
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_slug($value);
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * The subscription must always belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id', 'id');
    }

    /**
     * The subscription may have many usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage(): hasMany
    {
        return $this->hasMany(PlanSubscriptionUsage::class, 'subscription_id', 'id');
    }

    /**
     * Check if subscription is active.
     *
     * @return bool
     */
    public function active()
    {
        return ! $this->ended() || $this->onTrial();
    }

    /**
     * Check if subscription is inactive.
     *
     * @return bool
     */
    public function inactive()
    {
        return ! $this->active();
    }

    /**
     * Check if subscription is currently on trial.
     *
     * @return bool
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at ? Carbon::now()->lt($this->trial_ends_at) : false;
    }

    /**
     * Check if subscription is canceled.
     *
     * @return bool
     */
    public function canceled(): bool
    {
        return $this->canceled_at ? Carbon::now()->gte($this->canceled_at) : false;
    }

    /**
     * Check if subscription period has ended.
     *
     * @return bool
     */
    public function ended(): bool
    {
        return $this->ends_at ? Carbon::now()->gte($this->ends_at) : false;
    }

    /**
     * Cancel subscription.
     *
     * @param bool $immediately
     *
     * @return $this
     */
    public function cancel($immediately = false): self
    {
        $this->canceled_at = Carbon::now();

        if ($immediately) {
            $this->ends_at = $this->canceled_at;
        }

        $this->save();

        return $this;
    }

    /**
     * Change subscription plan.
     *
     * @param \Rinvex\Subscribable\Models\Plan $plan
     *
     * @return $this
     */
    public function changePlan(Plan $plan): self
    {
        // If plans does not have the same billing frequency
        // (e.g., invoice_interval and invoice_period) we will update
        // the billing dates starting today, and sice we are basically creating
        // a new billing cycle, the usage data will be cleared.
        if ($this->plan->invoice_interval !== $plan->invoice_interval || $this->plan->invoice_period !== $plan->invoice_period) {
            $this->setNewPeriod($plan->invoice_interval, $plan->invoice_period);
            $this->usage()->delete();
        }

        // Attach new plan to subscription
        $this->plan_id = $plan->id;
        $this->save();

        return $this;
    }

    /**
     * Renew subscription period.
     *
     * @throws \LogicException
     *
     * @return $this
     */
    public function renew(): self
    {
        if ($this->ended() && $this->canceled()) {
            throw new LogicException('Unable to renew canceled ended subscription.');
        }

        $subscription = $this;

        DB::transaction(function () use ($subscription) {
            // Clear usage data
            $subscription->usage()->delete();

            // Renew period
            $subscription->setNewPeriod();
            $subscription->canceled_at = null;
            $subscription->save();
        });

        return $this;
    }

    /**
     * Scope subscriptions by user id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $userId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUserId(Builder $builder, int $userId): Builder
    {
        return $builder->where('user_id', $userId);
    }

    /**
     * Scope subscriptions with ending trial.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $dayRange
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindEndingTrial(Builder $builder, int $dayRange = 3): Builder
    {
        $from = Carbon::now();
        $to = Carbon::now()->addDays($dayRange);

        return $builder->whereBetween('trial_ends_at', [$from, $to]);
    }

    /**
     * Scope subscriptions with ended trial.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindEndedTrial(Builder $builder): Builder
    {
        return $builder->where('trial_ends_at', '<=', Carbon::now());
    }

    /**
     * Scope subscriptions with ending periods.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $dayRange
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindEndingPeriod(Builder $builder, int $dayRange = 3): Builder
    {
        $from = Carbon::now();
        $to = Carbon::now()->addDays($dayRange);

        return $builder->whereBetween('ends_at', [$from, $to]);
    }

    /**
     * Scope subscriptions with ended periods.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindEndedPeriod(Builder $builder): Builder
    {
        return $builder->where('ends_at', '<=', Carbon::now());
    }

    /**
     * Set new subscription period.
     *
     * @param string $invoice_interval
     * @param int    $invoice_period
     * @param string $start
     *
     * @return $this
     */
    protected function setNewPeriod($invoice_interval = '', $invoice_period = '', $start = '')
    {
        if (empty($invoice_interval)) {
            $invoice_interval = $this->plan->invoice_interval;
        }

        if (empty($invoice_period)) {
            $invoice_period = $this->plan->invoice_period;
        }

        $period = new Period($invoice_interval, $invoice_period, $start);

        $this->starts_at = $period->getStartDate();
        $this->ends_at = $period->getEndDate();

        return $this;
    }

    /**
     * Record feature usage.
     *
     * @param string $featureSlug
     * @param int    $uses
     *
     * @return \Rinvex\Subscribable\Models\PlanSubscriptionUsage
     */
    public function recordFeatureUsage(string $featureSlug, int $uses = 1, bool $incremental = true): PlanSubscriptionUsage
    {
        $feature = $this->plan->features()->where('slug', $featureSlug)->first();

        $usage = $this->usage()->firstOrNew([
            'subscription_id' => $this->id,
            'feature_id' => $feature->id,
        ]);

        if ($feature->resettable_period) {
            // Set expiration date when the usage record is new or doesn't have one.
            if (is_null($usage->valid_until)) {
                // Set date from subscription creation date so the reset
                // period match the period specified by the subscription's plan.
                $usage->valid_until = $feature->getResetDate($this->created_at);
            } elseif ($usage->expired()) {
                // If the usage record has been expired, let's assign
                // a new expiration date and reset the uses to zero.
                $usage->valid_until = $feature->getResetDate($usage->valid_until);
                $usage->used = 0;
            }
        }

        $usage->used = ($incremental ? $usage->used + $uses : $uses);

        $usage->save();

        return $usage;
    }

    /**
     * Reduce usage.
     *
     * @param string $featureSlug
     * @param int    $uses
     *
     * @return \Rinvex\Subscribable\Models\PlanSubscriptionUsage|null
     */
    public function reduceFeatureUsage(string $featureSlug, int $uses = 1)
    {
        $usage = $this->usage()->byFeatureSlug($featureSlug)->first();

        if (is_null($usage)) {
            return;
        }

        $usage->used = max($usage->used - $uses, 0);

        $usage->save();

        return $usage;
    }

    /**
     * Determine if the feature can be used.
     *
     * @param string $featureSlug
     *
     * @return bool
     */
    public function canUseFeature(string $featureSlug): bool
    {
        $featureValue = $this->getFeatureValue($featureSlug);
        $usage = $this->usage()->byFeatureSlug($featureSlug)->first();

        if ($featureValue === 'true') {
            return true;
        }

        // If the feature value is zero, let's return false since
        // there's no uses available. (useful to disable countable features)
        if ($usage->expired() || is_null($featureValue) || $featureValue === '0' || $featureValue === 'false') {
            return false;
        }

        // Check for available uses
        return $this->getFeatureRemainings($featureSlug) > 0;
    }

    /**
     * Get how many times the feature has been used.
     *
     * @param string $featureSlug
     *
     * @return int
     */
    public function getFeatureUsage(string $featureSlug): int
    {
        $usage = $this->usage()->byFeatureSlug($featureSlug)->first();

        return ! $usage->expired() ? $usage->used : 0;
    }

    /**
     * Get the available uses.
     *
     * @param string $featureSlug
     *
     * @return int
     */
    public function getFeatureRemainings(string $featureSlug): int
    {
        return $this->getFeatureValue($featureSlug) - $this->getFeatureUsage($featureSlug);
    }

    /**
     * Get feature value.
     *
     * @param string $featureSlug
     *
     * @return mixed
     */
    public function getFeatureValue(string $featureSlug)
    {
        $feature = $this->plan->features()->where('slug', $featureSlug)->first();

        return $feature->value ?? null;
    }
}
