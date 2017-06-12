<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Models;

use Carbon\Carbon;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rinvex\Subscribable\Models\PlanSubscriptionUsage.
 *
 * @property int                                               $id
 * @property int                                               $subscription_id
 * @property int                                               $feature_id
 * @property int                                               $used
 * @property \Carbon\Carbon                                    $valid_until
 * @property \Carbon\Carbon                                    $created_at
 * @property \Carbon\Carbon                                    $updated_at
 * @property \Carbon\Carbon                                    $deleted_at
 * @property-read \Rinvex\Subscribable\Models\PlanFeature      $feature
 * @property-read \Rinvex\Subscribable\Models\PlanSubscription $subscription
 *
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage byFeatureSlug($featureSlug)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereFeatureId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereSubscriptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanSubscriptionUsage whereValidUntil($value)
 * @mixin \Eloquent
 */
class PlanSubscriptionUsage extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'subscription_id',
        'feature_id',
        'used',
        'valid_until',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'valid_until',
        'deleted_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = ['validating', 'validated'];

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

        $this->setTable(config('rinvex.subscribable.tables.plan_subscription_usage'));
        $this->setRules([
            'subscription_id' => 'required|integer|exists:'.config('rinvex.subscribable.tables.plan_subscriptions').',id',
            'feature_id' => 'required|integer|exists:'.config('rinvex.subscribable.tables.plan_features').',id',
            'used' => 'required|numeric',
            'valid_until' => 'nullable|date',
        ]);
    }

    /**
     * Subscription usage always belongs to a plan feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(PlanFeature::class, 'feature_id', 'id');
    }

    /**
     * Subscription usage always belongs to a plan subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(PlanSubscription::class, 'subscription_id', 'id');
    }

    /**
     * Scope subscription usage by feature slug.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $featureSlug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFeatureSlug(Builder $builder, string $featureSlug): Builder
    {
        $feature = PlanFeature::where('slug', $featureSlug)->first();

        return $builder->where('feature_id', $feature->id ?? null);
    }

    /**
     * Check whether usage has been expired or not.
     *
     * @return bool
     */
    public function expired(): bool
    {
        if (is_null($this->valid_until)) {
            return false;
        }

        return Carbon::now()->gte($this->valid_until);
    }
}
