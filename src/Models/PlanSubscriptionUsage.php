<?php

declare(strict_types=1);

namespace Namdeveloper\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Namdeveloper\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Builder;
use Namdeveloper\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage.
 *
 * @property int                                               $id
 * @property int                                               $subscription_id
 * @property int                                               $feature_id
 * @property int                                               $used
 * @property \Carbon\Carbon                                    $valid_until
 * @property \Carbon\Carbon                                    $created_at
 * @property \Carbon\Carbon                                    $updated_at
 * @property \Carbon\Carbon                                    $deleted_at
 * @property-read \Namdeveloper\Subscriptions\Models\PlanFeature      $feature
 * @property-read \Namdeveloper\Subscriptions\Models\PlanSubscription $subscription
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage byFeatureName($featureName)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereFeatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Namdeveloper\Subscriptions\Models\PlanSubscriptionUsage whereValidUntil($value)
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
    protected $casts = [
        'subscription_id' => 'integer',
        'feature_id' => 'integer',
        'used' => 'integer',
        'valid_until' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
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

        $this->setTable(config('namdeveloper.subscriptions.tables.plan_subscription_usage'));
        $this->setRules([
            'subscription_id' => 'required|integer|exists:'.config('namdeveloper.subscriptions.tables.plan_subscriptions').',id',
            'feature_id' => 'required|integer|exists:'.config('namdeveloper.subscriptions.tables.plan_features').',id',
            'used' => 'required|integer',
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
        return $this->belongsTo(config('namdeveloper.subscriptions.models.plan_feature'), 'feature_id', 'id');
    }

    /**
     * Subscription usage always belongs to a plan subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(config('namdeveloper.subscriptions.models.plan_subscription'), 'subscription_id', 'id');
    }

    /**
     * Scope subscription usage by feature name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $featureName
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFeatureName(Builder $builder, string $featureName): Builder
    {
        $feature = PlanFeature::where('name', $featureName)->first();

        return $builder->where('feature_id', $feature->getKey() ?? null);
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

        return now()->gte($this->valid_until);
    }
}
