<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Voucher extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Voucher::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'code';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'code',
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
            ID::make()->sortable(),
            Text::make('Code')
                ->resolveUsing(function ($code) {
                    return strtoupper($code);
                })
                ->creationRules('required', 'unique:vouchers,code')
                ->updateRules('unique:vouchers,code,{{resourceId}}'),
            Text::make('Name')->rules('required'),
            Textarea::make('Description')->rules('nullable'),
            Number::make('Uses')->exceptOnForms(),
            Number::make('Max Uses')->min(1)->step(1)->rules('nullable'),
            Select::make('Type')->options([
                'percentage' => 'percentage',
                'price' => 'Fixed Price',
            ])->onlyOnForms()->rules('required'),
            Badge::make('Type')->map([
                'percentage' => 'warning',
                'price' => 'success',
            ])->exceptOnForms(),
            Number::make('Discount Amount')->rules('required'),
            Boolean::make('Status')->default(true),
            DateTime::make('Starts At')->rules('nullable'),
            DateTime::make('Expires At')->rules('nullable'),
            Select::make('duration_type')->options([
                'no_of_days' => 'no_of_days',
                'until_fixed_date' => 'until_fixed_date',
            ])->rules('nullable'),
            Number::make('Duration')->min(1)->step(1)->rules('nullable'),
            DateTime::make('discount_until')->rules('nullable'),
            BelongsToMany::make('vendors'),
            BelongsToMany::make('Users'),
            HasMany::make('Voucher Usage Logs', 'voucherUsageLogs', VoucherUsageLog::class),

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
        return [
            (new Actions\Activate)
                ->confirmText('Are you sure you want to activate this Voucher(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\DeActivate)
                ->confirmText('Are you sure you want to de-activate this Voucher(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),
        ];
    }
}
