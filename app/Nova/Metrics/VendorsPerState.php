<?php

namespace App\Nova\Metrics;

use App\Models\State;
use App\Models\Vendor;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class VendorsPerState extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Vendor::where('approved', true)->orderBy('aggregate', 'desc'), 'state_id')->label(function ($value) {
            switch ($value) {
                case null:
                    return 'None';
                default:
                    $state = State::find($value);

                    return ucfirst($state->name);
            }
        });
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
        return 'vendors-per-state';
    }
}
