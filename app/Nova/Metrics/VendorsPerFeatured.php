<?php

namespace App\Nova\Metrics;

use App\Models\Vendor;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class VendorsPerFeatured extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Vendor::where('approved', true)->orderBy('aggregate', 'desc'), 'featured')->label(function ($value) {
            switch ($value) {
                case null:
                    return 'un-featured';
                default:
                    $label = $value ? 'featured' : 'un-featured';

                    return ucfirst($label);
            }
        })->colors([
            1 => '#28a745',
            0 => '#dc3545',
            null => '#dc3545!',
        ]);
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
        return 'vendors-per-featured';
    }
}
