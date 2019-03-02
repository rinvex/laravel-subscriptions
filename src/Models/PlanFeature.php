<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Models;

use Carbon\Carbon;
use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Subscriptions\Services\Period;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Rinvex\Subscriptions\Traits\BelongsToPlan;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rinvex\Subscriptions\Models\PlanFeature.
 *
 * @property int                                                                                                $id
 * @property int                                                                                                $plan_id
 * @property string                                                                                             $slug
 * @property array                                                                                              $title
 * @property array                                                                                              $description
 * @property string                                                                                             $value
 * @property int                                                                                                $resettable_period
 * @property string                                                                                             $resettable_interval
 * @property int                                                                                                $sort_order
 * @property \Carbon\Carbon|null                                                                                $created_at
 * @property \Carbon\Carbon|null                                                                                $updated_at
 * @property \Carbon\Carbon|null                                                                                $deleted_at
 * @property-read \Rinvex\Subscriptions\Models\Plan                                                             $plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscriptions\Models\PlanSubscriptionUsage[] $usage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature byPlanId($planId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereResettableInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereResettablePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\PlanFeature whereValue($value)
 * @mixin \Eloquent
 */
class PlanFeature extends Model implements Sortable
{
    use HasSlug;
    use BelongsToPlan;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'plan_id',
        'slug',
        'name',
        'description',
        'value',
        'resettable_period',
        'resettable_interval',
        'sort_order',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'plan_id' => 'integer',
        'slug' => 'string',
        'value' => 'string',
        'resettable_period' => 'integer',
        'resettable_interval' => 'string',
        'sort_order' => 'integer',
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
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The sortable settings.
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'sort_order',
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

        $this->setTable(config('rinvex.subscriptions.tables.plan_features'));
        $this->setRules([
            'plan_id' => 'required|integer|exists:'.config('rinvex.subscriptions.tables.plans').',id',
            'slug' => 'required|alpha_dash|max:150|unique:'.config('rinvex.subscriptions.tables.plan_features').',slug',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'value' => 'required|string',
            'resettable_period' => 'sometimes|integer',
            'resettable_interval' => 'sometimes|in:hour,day,week,month',
            'sort_order' => 'nullable|integer|max:10000000',
        ]);
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
     * The plan feature may have many subscription usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage(): HasMany
    {
        return $this->hasMany(config('rinvex.subscriptions.models.plan_subscription_usage'), 'feature_id', 'id');
    }

    /**
     * Get feature's reset date.
     *
     * @param string $dateFrom
     *
     * @return \Carbon\Carbon
     */
    public function getResetDate(Carbon $dateFrom): Carbon
    {
        $period = new Period($this->resettable_interval, $this->resettable_period, $dateFrom ?? now());

        return $period->getEndDate();
    }
}
