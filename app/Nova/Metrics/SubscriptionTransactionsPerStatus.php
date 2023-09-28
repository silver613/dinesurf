<?php

namespace App\Nova\Metrics;

use App\Models\Transaction;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class SubscriptionTransactionsPerStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Transaction::where('type', 'subscription')->orderBy('aggregate', 'desc'), 'status')->colors([
            'succeeded' => '#38a169',
            'success' => '#38a169',
            'failed' => '#e53e3e',
            'pending' => '#fff382',
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
        return 'subscription-transactions-per-status';
    }
}
