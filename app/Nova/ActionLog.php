<?php

namespace App\Nova;

use App\Nova\Metrics\ActionsPerRoute;
use App\Nova\Metrics\ActionsPerType;
use App\Nova\Metrics\EmailsSentPerVendor;
use App\Nova\Metrics\TextsSentPerVendor;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ActionLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ActionLog::class;

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
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            DateTime::make('created_at')->hideWhenUpdating(),

            Stack::make('Model', [
                $this->vendor ? Text::make('vendor')->displayUsing(function () {
                    return 'Vendor: <a class="link-default" href="/admin/resources/vendors/'.$this->vendor->id.'">'.$this->vendor->company_name.'</a>';
                })->asHtml() : Text::make('vendor')->displayUsing(function () {
                    return '';
                }),

                $this->user ? Text::make('user')->displayUsing(function () {
                    return 'User: <a class="link-default" href="/admin/resources/users/'.$this->user->id.'">'.$this->user->first_name.' '.$this->user->last_name.'</a>';
                })->asHtml() : Text::make('user')->displayUsing(function () {
                    return '';
                }),

                $this->guest ? Text::make('guest')->displayUsing(function () {
                    return 'guest: <a class="link-default" href="/admin/resources/guests/'.$this->guest->id.'">'.$this->guest->first_name.' '.$this->guest->last_name.'</a>';
                })->asHtml() : Text::make('guest')->displayUsing(function () {
                    return '';
                }),

                $this->reservation ? Text::make('reservation')->displayUsing(function () {
                    return 'reservation: <a class="link-default" href="/admin/resources/reservations/'.$this->reservation->id.'">'.$this->reservation->first_name.' '.$this->reservation->last_name.'</a>';
                })->asHtml() : Text::make('reservation')->displayUsing(function () {
                    return '';
                }),

            ])->hideWhenUpdating(),
            Text::make('route'),
            Text::make('type'),
            Textarea::make('content'),
            Text::make('action_by'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            (new EmailsSentPerVendor())->width('1/2'),
            (new TextsSentPerVendor())->width('1/2'),
            (new ActionsPerRoute())->width('1/2'),
            (new ActionsPerType())->width('1/2'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
