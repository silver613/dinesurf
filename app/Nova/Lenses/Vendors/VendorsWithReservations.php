<?php

namespace App\Nova\Lenses\Vendors;

use App\Nova\Filters\NumberOfReservations;
use App\Nova\Metrics\ReservationsPerVendor;
use App\Nova\Metrics\TotalVendorsWithReservations;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class VendorsWithReservations extends Lens
{
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
            $query->where('approved', true)->has('reservations')
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
            DateTime::make('created_at')->hideWhenUpdating(),
            Text::make('Impersonate', function () {
                $link = URL::to('/impersonate-vendor?vendor_id='.$this->id);

                return "<a href='$link' target='_blank'>Impersonate Vendor</a>";
            })->asHtml(),
            Text::make('company_name'),
            Text::make('Reservations', function () {
                return $this->reservations->count();
            }),
            Avatar::make('Photo', 'profile_photo_path')->disk('s3'),
            Image::make('banner')->disk('s3'),
            Boolean::make('open')->readonly(),
            Boolean::make('approved'),
            Boolean::make('featured'),
            Boolean::make('verified'),
            Boolean::make('free_trial')->readonly(),
            Boolean::make('subscribed'),
            // DateTime::make('free_trial_start')->format('DD-MMM-YYYY, hh:mm:ssa')->hideFromIndex()->readonly(),
            // DateTime::make('free_trial_end')->format('DD-MMM-YYYY, hh:mm:ssa')->hideFromIndex()->readonly(),
            Text::make('email'),
            Text::make('trip_advisor_id'),
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
        return [
            new TotalVendorsWithReservations,
            (new ReservationsPerVendor())->width('2/3'),
        ];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new NumberOfReservations,
        ];
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
        return 'vendors-vendors-with-reservations';
    }
}
