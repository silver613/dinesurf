<?php

namespace App\Nova\Metrics;

use App\Models\Vendor;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class VendorsPerFreeTrial extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Vendor::orderBy('aggregate', 'desc')->where('subscribed', false)->where('approved', true), 'free_trial')->label(function ($value) {
            switch ($value) {
                case null:
                    return 'Not on Free Trial';
                default:
                    $label = $value ? 'On Free Trial' : 'Not on Free Trial';

                    return ucfirst($label);
            }
        })->colors([
            1 => '#28a745',
            0 => '#ffc107',
            null => '#ffc107',
        ]);
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
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
        return 'vendors-per-free-trial';
    }
}
