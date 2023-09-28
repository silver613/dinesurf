<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        if ($request->source) {
            session(['source' => $request->source]);
        }

        if ($request->campaign_id) {
            session(['campaign_id' => $request->campaign_id]);
        }

        return array_merge(parent::share($request), [
            'appName' => config('app.name'),
            'auth' => [
                'user' => $request->user()
                    ? $request->user()
                    : null,
                'vendor' => session('current_vendor'),
            ],
            'page' => $request->getQueryString() == '' ? $request->path() : $request->path().'?'.$request->getQueryString(),
            'search' => [
                'query' => session('query'),
                'state_id' => session('state_id'),
                'city_id' => session('city_id'),
            ],
            'tracking' => [
                'source' => session('source'),
                'campaign_id' => session('campaign_id'),
            ],
            'csrf_token' => csrf_token(),
            'permissions' => Jetstream::$permissions,
            'intendedUrl' => $request->intendedUrl,
        ]);
    }
}
