<?php

namespace App\Nova;

use App\Nova\Actions\Excel\DownloadTransactionsExcel;
use App\Nova\Metrics\TotalSuccessfullTransactionsVolume;
use App\Nova\Metrics\TransactionsPerStatus;
use App\Nova\Metrics\TransactionsToday;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'reference';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'reference',
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
            DateTime::make('Date', 'created_at')->hideWhenUpdating(),
            Badge::make('Status')->map([
                'otp' => 'info',
                'ongoing' => 'info',
                'verified' => 'success',
                'success' => 'success',
                'succeeded' => 'success',
                'pending' => 'warning',
                'requires_payment_method' => 'warning',
                'failed' => 'danger',
                'abandoned' => 'danger',
                'refunded' => 'danger',
                null => 'danger',
            ])->hideWhenUpdating(),
            Select::make('Currency')->options([
                'NGN' => 'NGN',
                'USD' => 'USD',
            ])->onlyOnForms(),
            Number::make('Amount')->min(1)->step(0.01)->onlyOnForms()->hideWhenUpdating(),
            Text::make('Amount')->displayUsing(function () {
                $symbol = null;
                if ($this->currency == 'NGN') {
                    $symbol = '₦';
                }
                if ($this->currency == 'USD') {
                    $symbol = '$';
                }

                return $symbol.number_format($this->amount, 2);
            })->exceptOnForms()->hideWhenUpdating(),
            // Text::make('Currency'),
            BelongsTo::make('User')->hideWhenUpdating(),
            BelongsTo::make('Vendor')->hideWhenUpdating(),
            BelongsTo::make('PlanFrequency')->hideWhenUpdating(),
            Text::make('Reference')->hideWhenUpdating(),
            BelongsTo::make('Voucher')->hideWhenUpdating(),
            Text::make('Type')->hideWhenUpdating(),
            Text::make('Payment Method')->hideWhenUpdating(),
            Boolean::make('Active')->hideWhenUpdating(),
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
            (new TransactionsToday())->width('1/3'),
            (new TransactionsPerStatus())->width('2/3'),
            (new TotalSuccessfullTransactionsVolume())->width('1/3'),
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
        return [
            new \CompanyApi\CustomDateFilter\CustomDateFilter,
        ];
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
            // (new DownloadTransactionsExcel)->withHeadings(
            //     'id',
            //     'created_at',
            //     'status',
            //     'currency',
            //     'amount',
            //     'user_name',
            //     'email',
            //     'phone',
            //     'vendor',
            //     'stall',
            //     'reference',
            //     'type',
            //     'payment_method',
            // ),
            // (new Actions\UpdateTransactionStatus)
            // ->confirmText('Are you sure you want to Update this Transaction(s) Status(es)?')
            // ->confirmButtonText('Yes')
            // ->showInline(),
        ];
    }
}
