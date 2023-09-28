<?php

namespace App\Nova\Lenses\Vendors;

use App\Nova\Filters\NumberOfReservations;
use App\Nova\Metrics\VendorsPerFeatured;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class FeaturedVendors extends Lens
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
            $query->where('featured', true)->where('approved', true)
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Impersonate', function () {
                $link = URL::to('/impersonate-vendor?vendor_id='.$this->id);

                return "<a href='$link' target='_blank'>Impersonate Vendor</a>";
            })->asHtml(),
            Text::make('first_name')->hideFromIndex()->readonly(),
            Text::make('last_name')->hideFromIndex()->readonly(),
            Text::make('company_name'),
            Text::make('company_address')->hideFromIndex(),
            Avatar::make('Photo', 'profile_photo_path')->disk('s3'),
            Image::make('banner')->disk('s3')->hideFromIndex(),
            Badge::make('type')->map([
                'restaurant' => 'warning',
            ])->hideFromIndex(),
            Text::make('phone_number')->hideFromIndex()->readonly(),
            Boolean::make('open'),
            Boolean::make('approved'),
            Boolean::make('featured'),
            Boolean::make('free_trial')->readonly(),
            Boolean::make('subscribed'),
            Text::make('email'),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [

            (new VendorsPerFeatured())->width('1/3'),
        ];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
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
        return 'vendors-featured-vendors';
    }
}
