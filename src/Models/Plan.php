<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Models;

use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rinvex\Subscriptions\Models\Plan.
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
 * @property \Carbon\Carbon|null                                                                          $created_at
 * @property \Carbon\Carbon|null                                                                          $updated_at
 * @property \Carbon\Carbon|null                                                                          $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscriptions\Models\PlanFeature[]      $features
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscriptions\Models\PlanSubscription[] $subscriptions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereActiveSubscribersLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereGraceInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereGracePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereInvoiceInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereInvoicePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereProrateDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereProrateExtendDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereProratePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereSignupFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereTrialInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereTrialPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Subscriptions\Models\Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Plan extends Model implements Sortable
{
    use HasSlug;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'price',
        'signup_fee',
        'currency',
        'trial_period',
        'trial_interval',
        'invoice_period',
        'invoice_interval',
        'grace_period',
        'grace_interval',
        'prorate_day',
        'prorate_period',
        'prorate_extend_due',
        'active_subscribers_limit',
        'sort_order',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
        'is_active' => 'boolean',
        'price' => 'float',
        'signup_fee' => 'float',
        'currency' => 'string',
        'trial_period' => 'integer',
        'trial_interval' => 'string',
        'invoice_period' => 'integer',
        'invoice_interval' => 'string',
        'grace_period' => 'integer',
        'grace_interval' => 'string',
        'prorate_day' => 'integer',
        'prorate_period' => 'integer',
        'prorate_extend_due' => 'integer',
        'active_subscribers_limit' => 'integer',
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

        $this->setTable(config('rinvex.subscriptions.tables.plans'));
        $this->setRules([
            'slug' => 'required|alpha_dash|max:150|unique:'.config('rinvex.subscriptions.tables.plans').',slug',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'is_active' => 'sometimes|boolean',
            'price' => 'required|numeric',
            'signup_fee' => 'required|numeric',
            'currency' => 'required|alpha|size:3',
            'trial_period' => 'sometimes|integer|max:10000',
            'trial_interval' => 'sometimes|in:hour,day,week,month',
            'invoice_period' => 'sometimes|integer|max:10000',
            'invoice_interval' => 'sometimes|in:hour,day,week,month',
            'grace_period' => 'sometimes|integer|max:10000',
            'grace_interval' => 'sometimes|in:hour,day,week,month',
            'sort_order' => 'nullable|integer|max:10000000',
            'prorate_day' => 'nullable|integer|max:150',
            'prorate_period' => 'nullable|integer|max:150',
            'prorate_extend_due' => 'nullable|integer|max:150',
            'active_subscribers_limit' => 'nullable|integer|max:10000',
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
     * The plan may have many features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(config('rinvex.subscriptions.models.plan_feature'), 'plan_id', 'id');
    }

    /**
     * The plan may have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(config('rinvex.subscriptions.models.plan_subscription'), 'plan_id', 'id');
    }

    /**
     * Check if plan is free.
     *
     * @return bool
     */
    public function isFree(): bool
    {
        return (float) $this->price <= 0.00;
    }

    /**
     * Check if plan has trial.
     *
     * @return bool
     */
    public function hasTrial(): bool
    {
        return $this->trial_period && $this->trial_interval;
    }

    /**
     * Check if plan has grace.
     *
     * @return bool
     */
    public function hasGrace(): bool
    {
        return $this->grace_period && $this->grace_interval;
    }

    /**
     * Get plan feature by the given slug.
     *
     * @param string $featureSlug
     *
     * @return \Rinvex\Subscriptions\Models\PlanFeature|null
     */
    public function getFeatureBySlug(string $featureSlug): ?PlanFeature
    {
        return $this->features()->where('slug', $featureSlug)->first();
    }

    /**
     * Activate the plan.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate the plan.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }
}
