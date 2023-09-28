<?php

namespace App\Nova;

use App\Nova\Filters\ApprovalStatus;
use App\Nova\Filters\NumberOfReservations;
use App\Nova\Filters\State;
use App\Nova\Metrics\ReservationsPerVendor;
use App\Nova\Metrics\TotalVendors;
use App\Nova\Metrics\VendorsPerApproval;
use App\Nova\Metrics\VendorsPerFeatured;
use App\Nova\Metrics\VendorsPerFreeTrial;
use App\Nova\Metrics\VendorsPerState;
use App\Nova\Metrics\VendorsToday;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Vendor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Vendor::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'company_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'company_name', 'trip_advisor_id', 'phone_number', 'email',
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
            DateTime::make('created_at')->hideWhenUpdating(),
            Text::make('Impersonate', function () {
                $link = URL::to('/impersonate-vendor?vendor_id='.$this->id);

                return "<a href='$link' target='_blank'>Impersonate Vendor</a>";
            })->asHtml(),
            Text::make('Reservation Link', function () {
                $link = URL::to('/reserve/'.$this->slug);

                return "<a href='$link' target='_blank'>Reservation Link</a>";
            })->asHtml(),
            Text::make('first_name')->hideFromIndex()->readonly(),
            Text::make('last_name')->hideFromIndex()->readonly(),
            Text::make('company_name'),
            BelongsTo::make('Owner', 'user', User::class),
            Text::make('company_address')->hideFromIndex(),
            Text::make('Reservations', function () {
                return $this->reservations->count();
            }),
            Avatar::make('Photo', 'profile_photo_path')->disk('s3'),
            Image::make('banner')->disk('s3'),
            Badge::make('type')->map([
                'restaurant' => 'warning',
            ])->hideFromIndex(),
            Text::make('phone_number')->hideFromIndex(),

            BelongsTo::make('state')->nullable(),
            BelongsTo::make('city')->nullable(),
            Boolean::make('open')->readonly(),
            Boolean::make('visible')->readonly(),
            Boolean::make('approved')->readonly(),
            Boolean::make('featured')->readonly(),
            Boolean::make('verified')->readonly(),
            Boolean::make('free_trial')->readonly(),
            Boolean::make('subscribed')->readonly(),
            // DateTime::make('free_trial_start')->format('DD-MMM-YYYY, hh:mm:ssa')->hideFromIndex()->readonly(),
            // DateTime::make('free_trial_end')->format('DD-MMM-YYYY, hh:mm:ssa')->hideFromIndex()->readonly(),
            Date::make('free_trial_start')->hideFromIndex()->readonly(),
            Date::make('free_trial_end')->hideFromIndex(),
            Text::make('email'),
            Number::make('party_size')->min(0)->hideFromIndex(),
            Text::make('open_time')->hideFromIndex()->hideWhenUpdating()->nullable(),
            Text::make('close_time')->hideFromIndex()->hideWhenUpdating()->nullable(),
            Text::make('description')->hideFromIndex(),
            Text::make('company_email')->hideFromIndex(),
            Text::make('company_phone')->hideFromIndex(),
            Text::make('cancellation_policy')->hideFromIndex(),
            Text::make('menu_link')->hideFromIndex(),
            Text::make('whatsapp_number')->hideFromIndex(),
            Text::make('facebook_link')->displayUsing(function () {
                if ($this->facebook_link) {
                    return "<a href='".$this->facebook_link."' target='_blank'>View </a>";
                }

                return '<span class="font-bold">--</span> -';
            })->asHtml()->hideFromIndex(),
            Text::make('instagram_link')->displayUsing(function () {
                if ($this->instagram_link) {
                    return "<a href='".$this->instagram_link."' target='_blank'>View </a>";
                }

                return '<span class="font-bold">--</span> -';
            })->asHtml()->hideFromIndex(),
            Text::make('twitter_link')->displayUsing(function () {
                if ($this->twitter_link) {
                    return "<a href='".$this->twitter_link."' target='_blank'>View </a>";
                }

                return '<span class="font-bold">--</span> -';
            })->asHtml()->hideFromIndex(),
            Text::make('youtube_link')->displayUsing(function () {
                if ($this->youtube_link) {
                    return "<a href='".$this->youtube_link."' target='_blank'>View </a>";
                }

                return '<span class="font-bold">--</span> -';
            })->asHtml()->hideFromIndex(),
            Text::make('linkedin_link')->displayUsing(function () {
                if ($this->linkedin_link) {
                    return "<a href='".$this->linkedin_link."' target='_blank'>View </a>";
                }

                return '<span class="font-bold">--</span> -';
            })->asHtml()->hideFromIndex(),
            Text::make('longitude')->hideFromIndex()->readonly(),
            Text::make('latitude')->hideFromIndex()->readonly(),
            HasMany::make('Deals'),
            HasMany::make('Reservations'),
            HasMany::make('Reviews'),
            HasMany::make('Guests'),
            BelongsToMany::make('Days'),
            BelongsToMany::make('Cuisines'),
            BelongsToMany::make('Features'),
            Text::make('trip_advisor_id'),
            BelongsToMany::make('campaigns'),
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
        return [
            (new TotalVendors())->width('1/4'),
            (new VendorsToday())->width('1/4'),
            (new VendorsPerFreeTrial())->width('1/2'),
            (new ReservationsPerVendor())->width('1/2'),
            (new VendorsPerState())->width('1/2'),
            (new VendorsPerApproval())->width('1/2'),
            (new VendorsPerFeatured())->width('1/2'),
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
            // new NumberOfReservations,
            new ApprovalStatus,
            new State,
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
        return [
            new Lenses\Vendors\SubscribedVendors(),
            new Lenses\Vendors\VendorsOnFreeTrial(),
            new Lenses\Vendors\VendorsNotOnFreeTrial(),
            new Lenses\Vendors\FeaturedVendors(),
            new Lenses\Vendors\ApprovedVendors(),
            new Lenses\Vendors\UnApprovedVendors(),
            new Lenses\Vendors\VendorsWithReservations(),
            new Lenses\Vendors\VendorsWithOutReservations(),
            new Lenses\Vendors\VendorsWithTripAdvisorId(),
        ];
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
                return [
                    'id' => $model->id,
                    'created_at' => Carbon::parse($model->created_at)->format('jS F Y'),
                    'first_name' => $model->first_name,
                    'last_name' => $model->last_name,
                    'company_name' => $model->company_name,
                    'company_address' => $model->company_address,
                    'type' => $model->type,
                    'email' => $model->email,
                    'phone_number' => $model->phone_number,
                    'approved' => $model->approved == 1 ? true : false,
                    'open' => $model->open == 1 ? true : false,
                    'verified' => $model->verified == 1 ? true : false,
                    'party_size' => $model->party_size,
                    'open_time' => Carbon::parse($model->open_time)->format('h:ia'),
                    'close_time' => Carbon::parse($model->close_time)->format('h:ia'),
                    'description' => $model->description,
                    'company_email' => $model->company_email,
                    'company_phone' => $model->company_phone,
                    'menu_link' => $model->menu_link,
                    'cancellation_policy' => $model->cancellation_policy,
                    'whatsapp_number' => $model->whatsapp_number,
                    'facebook_link' => $model->facebook_link,
                    'instagram_link' => $model->instagram_link,
                    'twitter_link' => $model->twitter_link,
                    'youtube_link' => $model->youtube_link,
                    'linkedin_link' => $model->linkedin_link,
                    'longitude' => $model->longitude,
                    'latitude' => $model->latitude,
                ];
            }),
            (new Actions\ToggleAcceptingReservations)
                ->confirmButtonText('Go')
                ->showInline(),
            (new Actions\ToggleVisibility)
                ->confirmButtonText('Go')
                ->showInline(),
            (new Actions\Vendors\EmailVendor)
                ->confirmButtonText('Send')
                ->showInline(),

            (new Actions\Vendors\ResendWelcomeMail)
                ->confirmButtonText('Send')
                ->showInline(),

            (new Actions\Verify)
                ->confirmText('Are you sure you want to verify this Vendor(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\UnVerify)
                ->confirmText('Are you sure you want to remove this Vendor(s) verifcation?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\Approve)
                ->confirmText('Are you sure you want to approve this Vendor(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\DisApprove)
                ->confirmText('Are you sure you want to dis-approve this Vendor(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\Feature)
                ->confirmText('Are you sure you want to feature this Vendor(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\UnFeature)
                ->confirmText('Are you sure you want to un-feature this Vendor(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),
            (new Actions\DownloadReserveWithGoogleFile)
                ->confirmButtonText('Go')
                ->showInline(),

            (new Actions\SendSms)
                ->confirmText('Are you sure you want to send this message?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\ChangeOwner)
                // ->confirmText('Are you sure you want to change the owners of this (these) vendors?')
                ->confirmButtonText('Yes')
                ->showInline(),

            (new Actions\LoadOnlineReviews)
                ->showInline(),

            (new Actions\LoadAllOnlineReviews)->standalone(),

            Actions\Vendors\Subscribe::make()->size('5xl')->confirmText('Are you sure you want to subscribe this (these) Vendor(s)?')
                ->confirmButtonText('Yes')
                ->showInline(),

        ];
    }
}
