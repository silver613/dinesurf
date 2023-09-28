<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AppSetting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AppSetting::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

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
            // ID::make(__('ID'), 'id')->sortable(),
            Number::make('reservations_percentage_fee(%)', 'reservations_percentage_fee')->min(0)->max(1)->step(0.01)->onlyOnForms(),
            Text::make('reservations_percentage_fee')->displayUsing(function () {
                return ($this->reservations_percentage_fee * 100).'%';
            })->exceptOnForms(),
            Number::make('reservations_cap_fee(₦)', 'reservations_cap_fee')->min(0)->step(0.01)->onlyOnForms(),
            Text::make('reservations_cap_fee')->displayUsing(function () {
                return '₦'.number_format($this->reservations_cap_fee, 2);
            })->exceptOnForms(),
            Number::make('events_percentage_fee(%)', 'events_percentage_fee')->min(0)->max(1)->step(0.01)->onlyOnForms(),
            Text::make('events_percentage_fee')->displayUsing(function () {
                return ($this->events_percentage_fee * 100).'%';
            })->exceptOnForms(),
            Number::make('events_cap_fee(₦)', 'events_cap_fee')->min(0)->step(0.01)->onlyOnForms(),
            Text::make('events_cap_fee')->displayUsing(function () {
                return '₦'.number_format($this->events_cap_fee, 2);
            })->exceptOnForms(),
            Number::make('orders_percentage_fee(%)', 'orders_percentage_fee')->min(0)->max(1)->step(0.01)->onlyOnForms(),
            Text::make('orders_percentage_fee')->displayUsing(function () {
                return ($this->orders_percentage_fee * 100).'%';
            })->exceptOnForms(),
            Number::make('orders_cap_fee(₦)', 'orders_cap_fee')->min(0)->step(0.01)->onlyOnForms(),
            Text::make('orders_cap_fee')->displayUsing(function () {
                return '₦'.number_format($this->orders_cap_fee, 2);
            })->exceptOnForms(),
            Number::make('paystack_percentage_fee(%)', 'paystack_percentage_fee')->min(0)->max(1)->step(0.01)->onlyOnForms()->readonly(),
            Text::make('paystack_percentage_fee')->displayUsing(function () {
                return ($this->paystack_percentage_fee * 100).'%';
            })->exceptOnForms(),
            Number::make('paystack_flat_fee(₦)', 'paystack_flat_fee')->min(0)->step(0.01)->onlyOnForms()->readonly(),
            Text::make('paystack_flat_fee')->displayUsing(function () {
                return '₦'.number_format($this->paystack_flat_fee, 2);
            })->exceptOnForms(),
            Number::make('paystack_cap_fee(₦)', 'paystack_cap_fee')->min(0)->step(0.01)->onlyOnForms()->readonly(),
            Text::make('paystack_cap_fee')->displayUsing(function () {
                return '₦'.number_format($this->paystack_cap_fee, 2);
            })->exceptOnForms(),
            Number::make('paystack_wave_under(₦)', 'paystack_wave_under')->min(0)->step(0.01)->onlyOnForms()->readonly(),
            Text::make('paystack_wave_under')->displayUsing(function () {
                return '₦'.number_format($this->paystack_wave_under, 2);
            })->exceptOnForms(),
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
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
