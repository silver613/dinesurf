<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Cuisine;
use App\Models\Day;
use App\Models\Feature;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vendor;
use App\Observers\CampaignObserver;
use App\Observers\ClientObserver;
use App\Observers\CuisineObserver;
use App\Observers\DayObserver;
use App\Observers\FeatureObserver;
use App\Observers\MenuCategoryItemObserver;
use App\Observers\MenuCategoryObserver;
use App\Observers\MenuObserver;
use App\Observers\ReservationObserver;
use App\Observers\ReviewObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\UserObserver;
use App\Observers\VendorObserver;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Inertia::share('appName', config('app.name'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Reservation::observe(ReservationObserver::class);
        Vendor::observe(VendorObserver::class);
        Day::observe(DayObserver::class);
        Cuisine::observe(CuisineObserver::class);
        Feature::observe(FeatureObserver::class);
        Review::observe(ReviewObserver::class);
        Campaign::observe(CampaignObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        Client::observe(ClientObserver::class);
        Menu::observe(MenuObserver::class);
        MenuCategory::observe(MenuCategoryObserver::class);
        MenuCategoryItem::observe(MenuCategoryItemObserver::class);
    }
}
