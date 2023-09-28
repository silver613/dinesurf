<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NumberOfUsersInvitingGuests;
use App\Nova\Metrics\ReservationsPerVendor;
use App\Nova\Metrics\ReservationsToday;
use App\Nova\Metrics\SubscriptionsPerPlan;
use App\Nova\Metrics\SubscriptionsPerStatus;
use App\Nova\Metrics\SubscriptionsToday;
use App\Nova\Metrics\SubscriptionTransactionsPerStatus;
use App\Nova\Metrics\TotalReservations;
use App\Nova\Metrics\TotalSubscriptionsAmount;
use App\Nova\Metrics\TotalVendors;
use App\Nova\Metrics\Users\AverageReturns;
use App\Nova\Metrics\Users\NewUsers;
use App\Nova\Metrics\Users\UsersLoggedInPerDay;
use App\Nova\Metrics\Users\UsersPerDay;
use App\Nova\Metrics\Users\UsersReturnedToday;
use App\Nova\Metrics\VendorsToday;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            // new Help,
            (new UsersPerDay())->width('1/3'),
            (new NewUsers())->help('This is all new users in the database.')->width('1/3'),
            (new UsersReturnedToday())->width('1/3'),

            (new TotalReservations())->width('1/3'),
            (new ReservationsToday())->width('1/3'),
            (new TotalVendors())->width('1/3'),
            (new ReservationsPerVendor())->width('2/3'),
            (new VendorsToday())->width('1/3'),
            (new SubscriptionsPerStatus())->width('1/3'),
            (new SubscriptionsPerPlan())->width('1/3'),
            (new TotalSubscriptionsAmount())->width('1/3'),
            (new SubscriptionsToday())->width('1/3'),
            (new SubscriptionTransactionsPerStatus())->width('1/3'),

            (new AverageReturns())->width('1/3'),
            (new NumberOfUsersInvitingGuests())->width('1/3'),
            // (new UsersLoggedInPerDay())->width("1/3"),
        ];
    }
}
