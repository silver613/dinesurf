<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ReservationsPerMonth;
use Illuminate\Http\Request;
use Laravel\Nova\Dashboard;

class Analytics extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new ReservationsPerMonth())->width('full'),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'analytics';
    }

    public function menu(Request $request)
    {
        return parent::menu($request)->withBadge(function () {
            return 'NEW!';
        });
    }
}
