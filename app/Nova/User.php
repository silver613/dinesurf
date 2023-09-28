<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Validation\Rules;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'first_name';

    public function title()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'first_name', 'last_name', 'email',
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

            Gravatar::make()->maxWidth(50),

            // Text::make('first_name')
            //     ->sortable(),

            // Text::make('last_name')
            //     ->sortable(),

            Text::make('Name')->displayUsing(function () {
                return  $this->first_name.' '.$this->last_name;
            }),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Phone Number'),

            Boolean::make('can_create_vendor'),

            Boolean::make('Is Admin', 'is_admin')->exceptOnForms(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            BelongsTo::make('campaign')->exceptOnForms(),
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
        return [];
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
        return [
            (new Actions\ToggleCanCreateVendor)
            ->confirmButtonText('Go')
            ->showInline(),

            ExportAsCsv::make()->withFormat(function ($model) {
                return [
                    'id' => $model->id,
                    'created_at' => Carbon::parse($model->created_at)->format('jS F Y'),
                    'first_name' => $model->first_name,
                    'last_name' => $model->last_name,
                    'email' => $model->email,
                    'phone' => $model->phone_number,
                    'birthday' => $model->birthday,
                ];
            }),

            (new Actions\Users\ResendWelcomeMail)
                ->canSee(function ($request) {
                    return in_array('users_edit', $request->user()->getAllPermissions()->pluck('name')->all());
                })
                ->confirmButtonText('Send')
                ->showInline(),
            (new Actions\Users\EmailUser)
                ->canSee(function ($request) {
                    return in_array('users_edit', $request->user()->getAllPermissions()->pluck('name')->all());
                })
                ->confirmButtonText('Send')
                ->showInline(),

            (new Actions\Users\MakeUserAnAdmin)
                ->confirmText('Are you sure you want to make this user(s) an/all Admin(s)?')
                ->confirmButtonText('Make')
                ->showInline(),

            (new Actions\Users\RemoveUserAsAdmin)
                ->confirmText('Are you sure you want to REVOME this user(s) as Admin(s)?')
                ->confirmButtonText('Revome')
                ->showInline(),

            (new Actions\SendSms)
                ->confirmText('Are you sure you want to send this message?')
                ->confirmButtonText('Yes')
                ->showInline(),

        ];
    }
}
