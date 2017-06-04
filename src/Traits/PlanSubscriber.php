<?php

declare(strict_types=1);

namespace Rinvex\Subscribable\Traits;

use Carbon\Carbon;
use Rinvex\Subscribable\Models\Plan;
use Rinvex\Subscribable\Services\Period;
use Illuminate\Database\Eloquent\Collection;
use Rinvex\Subscribable\Models\PlanSubscription;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PlanSubscriber
{
    /**
     * A model may have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(PlanSubscription::class, 'user_id', 'id');
    }

    /**
     * A model may have many active subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeSubscriptions(): Collection
    {
        return $this->subscriptions->reject->inactive();
    }

    /**
     * Get a subscription by slug.
     *
     * @param string $subscriptionSlug
     *
     * @return \Rinvex\Subscribable\Models\PlanSubscription|null
     */
    public function subscription(string $subscriptionSlug)
    {
        return $this->subscriptions()->where('slug', $subscriptionSlug)->first();
    }

    /**
     * Get subscribed plans.
     *
     * @return \Rinvex\Subscribable\Models\PlanSubscription|null
     */
    public function subscribedPlans()
    {
        $planIds = $this->subscriptions->reject->inactive()->pluck('plan_id')->unique();

        return Plan::whereIn('id', $planIds)->get();
    }

    /**
     * Check if the user subscribed to the given plan.
     *
     * @param int $planId
     *
     * @return bool
     */
    public function subscribedTo($planId): bool
    {
        $subscription = $this->subscriptions()->where('plan_id', $planId)->first();

        return $subscription && $subscription->active();
    }

    /**
     * Subscribe user to a new plan.
     *
     * @param string                           $subscription
     * @param \Rinvex\Subscribable\Models\Plan $plan
     *
     * @return \Rinvex\Subscribable\Models\PlanSubscription
     */
    public function newSubscription($subscription, Plan $plan)
    {
        $trial = new Period($plan->trial_interval, $plan->trial_period, new Carbon());
        $period = new Period($plan->invoice_interval, $plan->invoice_period, $trial->getEndDate());

        return $this->subscriptions()->create([
            'name' => $subscription,
            'plan_id' => $plan->id,
            'trial_ends_at' => $trial->getEndDate(),
            'starts_at' => $period->getStartDate(),
            'ends_at' => $period->getEndDate(),
        ]);
    }
}
