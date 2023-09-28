<?php

namespace App\Nova\Metrics;

use App\Models\Reservation;
use App\Models\Vendor;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ReservationsPerVendor extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Reservation::whereHas('vendor', function ($q) {
            $q->where('approved', true);
        })->orderBy('aggregate', 'desc'), 'vendor_id')->label(function ($value) {
            switch ($value) {
                case null:
                    return 'None';
                default:
                    $vendor = Vendor::find($value);

                    return $vendor ? ucfirst($vendor->company_name) : 'None';
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
        return 'reservations-per-vendor';
    }
}
