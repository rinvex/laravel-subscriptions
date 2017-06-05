<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rinvex\Subscribable\Models\Plan.
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
 * @property bool                                                                                         $prorate_day
 * @property bool                                                                                         $prorate_period
 * @property bool                                                                                         $prorate_extend_due
 * @property int                                                                                          $active_subscribers_limit
 * @property int                                                                                          $sort_order
 * @property \Carbon\Carbon                                                                               $created_at
 * @property \Carbon\Carbon                                                                               $updated_at
 * @property \Carbon\Carbon                                                                               $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscribable\Models\PlanFeature[]      $features
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Subscribable\Models\PlanSubscription[] $subscriptions
 *
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereActiveSubscribersLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereGraceInterval($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereGracePeriod($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereInvoiceInterval($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereInvoicePeriod($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereProrateDay($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereProrateExtendDue($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereProratePeriod($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereSignupFee($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereSortOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereTrialInterval($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereTrialPeriod($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Subscribable\Models\Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Plan extends Model
{
    use HasSlug;
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

        $this->setTable(config('rinvex.subscribable.tables.plans'));
        $this->setRules([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'slug' => 'required|alpha_dash|unique:'.config('rinvex.subscribable.tables.plans').',slug',
            'price' => 'numeric',
            'signup_fee' => 'numeric',
            'currency' => 'alpha|size:3',
            'trial_interval' => 'in:day,week,month,year',
            'invoice_interval' => 'in:day,week,month,year',
            'grace_interval' => 'in:day,week,month,year',
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
     * The plan may have many features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class, 'plan_id', 'id');
    }

    /**
     * The plan may have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(PlanSubscription::class, 'plan_id', 'id');
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
     * @return \Rinvex\Subscribable\Models\PlanFeature|null
     */
    public function getFeatureBySlug(string $featureSlug)
    {
        return $this->features()->where('slug', $featureSlug)->first();
    }
}
