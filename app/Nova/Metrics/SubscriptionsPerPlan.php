<?php

namespace App\Nova\Metrics;

use App\Models\Plan;
use App\Models\Subscription;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class SubscriptionsPerPlan extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Subscription::orderBy('aggregate', 'desc'), 'plan_id')->label(function ($value) {
            switch ($value) {
                case null:
                    return 'None';
                default:
                    $plan = Plan::find($value);

                    return ucfirst($plan->name);
            }
        });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'subscriptions-per-plan';
    }
}
