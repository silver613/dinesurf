<?php

namespace App\Nova\Lenses\Vendors;

use App\Nova\Metrics\TotalSubscribedVendors;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class SubscribedVendors extends Lens
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
            $query->where('subscribed', true)
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
            Text::make('Impersonate', function () {
                $link = URL::to('/impersonate-vendor?vendor_id='.$this->id);

                return "<a href='$link' target='_blank'>Impersonate Vendor</a>";
            })->asHtml(),
            Text::make('company_name'),
            Avatar::make('Photo', 'profile_photo_path')->disk('s3'),
            Text::make('Subscription', function () {
                $subscription = $this->subscriptions->last();

                if ($subscription) {
                    return "<a href='/admin/resources/subscriptions/$subscription->id' class='link-default'>".$subscription->plan->name.' - '.$subscription->planFrequency->duration_text.' | '.$subscription->vendor->company_name.'</a>';
                }

                return '-';
            })->asHtml(),
            Text::make('Plan Duration', function () {
                $subscription = $this->subscriptions->last();

                if ($subscription) {
                    return $subscription->plan_start.' - '.$subscription->plan_end;
                }

                return '-';
            })->asHtml(),
            Badge::make('type')->map([
                'restaurant' => 'warning',
            ])->hideFromIndex(),
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
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            (new TotalSubscribedVendors())->width('1/3'),
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
        return [];
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
        return 'vendors-subscribed-vendors';
    }
}
