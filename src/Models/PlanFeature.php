<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Models;

use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Subscribable\Services\Period;
use Spatie\Translatable\HasTranslations;
use Rinvex\Subscribable\Traits\BelongsToPlan;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rinvex\Subscribable\Models\PlanFeature.
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
 * @property-read \Rinvex\Subscribable\Models\Plan                                                             $plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscribable\Models\PlanSubscriptionUsage[] $usage
 *
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature byPlanId($planId)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature wherePlanId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereResettableInterval($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereResettablePeriod($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereSortOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\PlanFeature whereValue($value)
 * @mixin \Eloquent
 */
class PlanFeature extends Model
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
    protected $dates = [
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

        $this->setTable(config('rinvex.subscribable.tables.plan_features'));
        $this->setRules([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'slug' => 'required|alpha_dash|unique:'.config('rinvex.subscribable.tables.plan_features').',slug',
            'plan_id' => 'required|integer|exists:'.config('rinvex.subscribable.tables.plans').',id',
            'resettable_interval' => 'in:day,week,month,year',
            'value' => 'required',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        if (isset(static::$dispatcher)) {
            // Early auto generate slugs before validation
            static::$dispatcher->listen('eloquent.validating: '.static::class, function (self $model) {
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
     * The plan feature may have many subscription usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage(): HasMany
    {
        return $this->hasMany(PlanSubscriptionUsage::class, 'feature_id', 'id');
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
        $period = new Period($this->resettable_interval, $this->resettable_period, $dateFrom ?? new Carbon());

        return $period->getEndDate();
    }
}
