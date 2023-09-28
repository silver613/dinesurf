<?php

namespace App\Nova;

use App\Nova\Metrics\ReservationsCreatedByUser;
use App\Nova\Metrics\ReservationsCreatedByVendor;
use App\Nova\Metrics\ReservationsPerVendor;
use App\Nova\Metrics\ReservationsToday;
use App\Nova\Metrics\TotalReservations;
use Carbon\Carbon;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Reservation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Reservation::class;

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
            ID::make(__('ID'), 'id')->sortable(),
            // DateTime::make('Date', 'created_at')->format('DD-MMM-YYYY, hh:mm:ssa')->hideWhenUpdating(),
            DateTime::make('created_at')->hideWhenUpdating(),
            // BelongsTo::make('user')->hideWhenUpdating(),
            Stack::make('User', [
                $this->user ? BelongsTo::make('user') : Text::make('lil')->displayUsing(function () {
                }),
                ! $this->user ? BelongsTo::make('guest') : Text::make('lil')->displayUsing(function () {
                }),
            ])->hideWhenUpdating(),
            Text::make('Phone'),
            BelongsTo::make('vendor')->hideWhenUpdating(),
            Number::make('party_size')->min(0)->hideWhenUpdating(),
            // Date::make('date')->hideWhenUpdating(),
            Text::make('Date')->displayUsing(function () {
                return '<span class="font-bold">'.$this->date.'</span>';
            })->asHtml()->hideWhenUpdating(),
            // SuperDatePicker::make("date")->hideWhenUpdating(),
            Text::make('time')->hideWhenUpdating(),
            Text::make('source')->hideWhenUpdating(),
            Badge::make('Status')->map([
                'pending' => 'warning',
                'approved' => 'success',
                'cancelled' => 'danger',
            ])->hideWhenUpdating(),
            Boolean::make('created_by_vendor')->hideWhenUpdating(),
            HasMany::make('Invitations')->hideWhenUpdating(),
            BelongsTo::make('deal'),
            BelongsTo::make('campaign'),

            // Boolean::make('toggled_by_admin')->hideWhenUpdating(),
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
            (new ReservationsPerVendor())->width('1/2'),
            (new ReservationsToday())->width('1/4'),
            (new TotalReservations())->width('1/4'),
            (new ReservationsCreatedByVendor())->width('1/2'),
            (new ReservationsCreatedByUser())->width('1/2'),
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
            ExportAsCsv::make()->withFormat(function ($model) {
                $user = $model->user ? $model->user : $model->guest;

                return [
                    'id' => $model->id,
                    'created_at' => Carbon::parse($model->created_at)->format('jS F Y'),
                    'user_name' => $user->first_name.' '.$user->last_name,
                    'user_email' => $user->email,
                    'user_phone_number' => $user->phone_number ? $user->phone_number : $user->phone,
                    'user_id' => $user->id,
                    'vendor' => $model->vendor ? $model->vendor->company_name : 'none',
                    'vendor_id' => $model->vendor ? $model->vendor->id : 'none',
                    'party_size' => $model->party_size,
                    'date' => Carbon::parse($model->date)->format('jS F Y'),
                    'start_time' => Carbon::parse($model->start_time)->format('h:ia'),
                    'end_time' => Carbon::parse($model->end_time)->format('h:ia'),
                    'time' => Carbon::parse($model->time)->format('h:ia'),
                    'status' => $model->status,
                    'approved' => $model->approved ? 'YES' : 'NO',
                    'created_by_vendor' => $model->created_by_vendor ? 'YES' : 'NO',
                ];
            }),
            (new Actions\UpdateReservationStatus)
                ->confirmButtonText('Go')
                ->showInline(),
        ];
    }
}
