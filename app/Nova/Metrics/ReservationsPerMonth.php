<?php

namespace App\Nova\Metrics;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class ReservationsPerMonth extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByMonths($request, Reservation::whereHas('vendor', function ($q) {
            $q->where('approved', true);
        }));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        $first_reservation = Reservation::first();
        $date_of_first_reservation = Carbon::parse($first_reservation->created_at);
        $diff = $date_of_first_reservation->diffInMonths(now()->addMonth());
        Log::info('first reservation user: '.json_encode($first_reservation->user));
        Log::info("diff: $diff");

        return [
            $diff => __('All Time'),
            // 24 => __('Past 2 years'),
            // 36 => __('Past 3 years'),
        ];
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
        return 'reservations-per-month';
    }
}
