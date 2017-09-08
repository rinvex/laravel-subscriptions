<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToPlan
{
    /**
     * The model always belongs to a plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(config('rinvex.subscriptions.models.plan'), 'plan_id', 'id');
    }

    /**
     * Scope models by plan id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $planId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPlanId(Builder $builder, int $planId): Builder
    {
        return $builder->where('plan_id', $planId);
    }
}
