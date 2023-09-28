<?php

namespace App\Nova\Lenses\Vendors;

use App\Nova\Filters\Vendors\Subscription\Active;
use App\Nova\Metrics\SubscriptionsPerPlan;
use App\Nova\Metrics\SubscriptionsPerStatus;
use App\Nova\Metrics\SubscriptionsPerType;
use App\Nova\Metrics\SubscriptionsToday;
use App\Nova\Metrics\SubscriptionTransactionsPerStatus;
use App\Nova\Metrics\TotalSubscriptions;
use App\Nova\Metrics\TotalSubscriptionsAmount;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class MostRecentSubscriptions extends Lens
{
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->orderby('created_at', 'desc')->whereRaw('Created_at IN (select MAX(Created_at) FROM subscriptions GROUP BY vendor_id)')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Boolean::make('active')->exceptOnForms(),
            BelongsTo::make('Plan')->exceptOnForms(),
            // BelongsTo::make('User')->exceptOnForms(),
            BelongsTo::make('Vendor')->exceptOnForms(),
            BelongsTo::make('Plan Frequency')->exceptOnForms(),
            DateTime::make('Starts', 'plan_start')->exceptOnForms(),
            DateTime::make('Next Billing Date', 'plan_end')->exceptOnForms(),
            BelongsTo::make('Payment', 'transaction', 'App\Nova\Transaction')->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [(new TotalSubscriptionsAmount())->width('1/3'),
            (new TotalSubscriptions())->width('1/3'),
            (new SubscriptionsToday())->width('1/3'),
            (new SubscriptionsPerPlan())->width('1/3'),
            (new SubscriptionsPerType())->width('1/3'),
            (new SubscriptionsPerStatus())->width('1/3'),
            (new SubscriptionTransactionsPerStatus())->width('1/3'), ];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [new Active];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'vendors-most-recent-subscriptions';
    }
}
