<?php

namespace App\Providers;

use App\Nova\ActionLog;
use App\Nova\AppSetting;
use App\Nova\Campaign;
use App\Nova\Client;
use App\Nova\Cuisine;
use App\Nova\Dashboards\Analytics;
use App\Nova\Dashboards\Main;
use App\Nova\Deal;
use App\Nova\FailedJob;
use App\Nova\Feature;
use App\Nova\Guest;
use App\Nova\Job;
use App\Nova\MailingList;
use App\Nova\Plan;
use App\Nova\Reservation;
use App\Nova\State as NovaState;
use App\Nova\Subscription;
use App\Nova\Transaction;
use App\Nova\Tutorial;
use App\Nova\User;
use App\Nova\UserLog;
use App\Nova\UserPercentageLog;
use App\Nova\UserRegistrationLog;
use App\Nova\Vendor;
use App\Nova\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\LogViewer\LogViewer;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class),
                MenuSection::dashboard(Analytics::class),

                MenuSection::make('Resources', [
                    MenuItem::resource(AppSetting::class),
                    MenuItem::resource(ActionLog::class),
                    MenuItem::resource(Client::class),
                    MenuItem::resource(Vendor::class),
                    MenuItem::resource(Reservation::class),
                    MenuItem::resource(Guest::class),
                    MenuItem::resource(Subscription::class),
                    MenuItem::resource(Transaction::class),
                    MenuItem::resource(Plan::class),
                    MenuItem::resource(Cuisine::class),
                    MenuItem::resource(Voucher::class),
                    MenuItem::resource(Deal::class),
                    MenuItem::resource(Campaign::class),
                    MenuItem::resource(Feature::class),
                    MenuItem::resource(MailingList::class),
                    MenuItem::resource(Tutorial::class),
                    MenuItem::resource(NovaState::class),
                    MenuItem::resource(User::class),
                    MenuItem::resource(UserLog::class),
                    MenuItem::resource(UserPercentageLog::class),
                    MenuItem::resource(UserRegistrationLog::class),
                ])->icon('home')->collapsable(),
                MenuSection::make('Roles & Permissions', [
                    MenuItem::make('Roles')
                        ->path('/resources/roles'),
                    // MenuItem::make('Permissions')
                    //     ->path('/resources/permissions')
                ])->icon('lock-closed')->collapsable(),
                MenuSection::make('Logs')->icon('desktop-computer')->path('/logs'),
                MenuSection::make('Queues', [
                    MenuItem::resource(Job::class),
                    MenuItem::resource(FailedJob::class),
                ])->icon('clock')->collapsable(),

            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [

        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            Main::make()->showRefreshButton(),
            Analytics::make()->showRefreshButton(),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
            new LogViewer,

        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
