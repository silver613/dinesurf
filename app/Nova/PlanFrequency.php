<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PlanFrequency extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PlanFrequency::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'duration_text';

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
            BelongsTo::make('Plan')->rules('required'),
            Select::make('Currency')->options([
                'NGN' => 'NGN',
                'USD' => 'USD',
            ])->onlyOnForms(),
            Number::make('Price', 'price')->onlyOnForms(),
            Text::make('price')->displayUsing(function () {
                $symbol = null;
                if ($this->currency == 'NGN') {
                    $symbol = '₦';
                }
                if ($this->currency == 'USD') {
                    $symbol = '$';
                }

                return $symbol.number_format($this->price, 2);
            })->exceptOnForms(),
            Select::make('Duration')->options([
                1 => 1,
                7 => 7,
                14 => 14,
                30 => 30,
                90 => 90,
                365 => 365,
            ])->displayUsingLabels()->rules(['required']),
            Select::make('Duration Text')->options([
                '24 hrs' => '24 hrs',
                'weekly' => 'weekly',
                '2 weeks' => '2 weeks',
                'monthly' => 'monthly',
                'quarterly' => 'quarterly',
                'yearly' => 'yearly',
            ])->displayUsingLabels()->rules(['required']),
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
