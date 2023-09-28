<?php

namespace App\Nova;

use App\Nova\Actions\ReSubscribe;
use App\Nova\Filters\Vendors\Subscription\Active;
use App\Nova\Lenses\Vendors\MostRecentSubscriptions;
use App\Nova\Metrics\SubscriptionsPerPlan;
use App\Nova\Metrics\SubscriptionsPerStatus;
use App\Nova\Metrics\SubscriptionsPerType;
use App\Nova\Metrics\SubscriptionsToday;
use App\Nova\Metrics\SubscriptionTransactionsPerStatus;
use App\Nova\Metrics\TotalSubscriptions;
use App\Nova\Metrics\TotalSubscriptionsAmount;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class Subscription extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Subscription::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'id';
    public function title()
    {
        return $this->plan->name.' - '.$this->planFrequency->duration_text.' | '.$this->vendor->company_name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
            BelongsTo::make('Subscribed By', 'subscribedBy', User::class)->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            (new TotalSubscriptionsAmount())->width('1/3'),
            (new TotalSubscriptions())->width('1/3'),
            (new SubscriptionsToday())->width('1/3'),
            (new SubscriptionsPerPlan())->width('1/3'),
            (new SubscriptionsPerType())->width('1/3'),
            (new SubscriptionsPerStatus())->width('1/3'),
            (new SubscriptionTransactionsPerStatus())->width('1/3'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [new Active];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [new MostRecentSubscriptions()];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            (new ReSubscribe)->showInline()->fullscreen(),
        ];
    }
}
