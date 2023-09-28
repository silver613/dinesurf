<?php

namespace App\Nova\Metrics;

use App\Models\Invitation;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class NumberOfUsersInvitingGuests extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $rservations = Invitation::select('reservation_id', DB::raw('count(*) as total'))
            ->groupBy('reservation_id')->pluck('reservation_id')->toArray();

        $users = Reservation::whereIn('id', $rservations)->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')->pluck('user_id')->toArray();

        return $this->result(count($users));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            // 30 => __('30 Days'),
            // 60 => __('60 Days'),
            // 365 => __('365 Days'),
            // 'TODAY' => __('Today'),
            // 'MTD' => __('Month To Date'),
            // 'QTD' => __('Quarter To Date'),
            // 'YTD' => __('Year To Date'),
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
        return 'number-of-users-inviting-guests';
    }
}
