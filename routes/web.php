<?php

use App\Http\Controllers\VendorController;
use App\Models\VendorPageViewCount;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// General unauthenticated routes
Route::get('/', 'PageController@index')->middleware('guest')->name('index');
Route::get('/test', 'PageController@test');

Route::get('/getViews/{id}', function ($id) {
    dd(VendorPageViewCount::where('vendor_id', $id)->get());
});

Route::post('/post-metrics', 'PageController@PostPageMerics')->name('metrics.post');
Route::get('/get-metrics/{name}', 'PageController@GetPageMerics')->name('metrics.get');

Route::get('/dashboard', 'PageController@dashboard')->name('dashboard');
Route::post('/log-current-url', 'PageController@logCurrentUrl')->name('log-current-url');
Route::get('/back', 'PageController@back')->name('back');
Route::get('/deals', 'PageController@deals')->name('deals');
Route::get('/restaurants-data/{id}', 'VendorController@getVendorData')->name('restaurants-data.index');
Route::get('/menu/{id}', 'PageController@getPersonalMenu')->name('menu');
Route::get('/menu/{id}/pdf', 'PageController@getPersonalMenuPdf')->name('menu.pdf');
Route::get('/event/{id}', 'PageController@getEvent')->name('getEvent');
Route::get('/deals', 'PageController@deals')->name('deals');
Route::get('/valentine-events', 'PageController@valentine')->name('valentine-events');
Route::get('/all-events', 'PageController@allEvents')->name('all-events');
Route::get('/reserve/{slug}', 'PageController@reserve')->name('reserve');

// Reservation
Route::get('/get-reservation/{id}', 'UserController@getReservation')->name('get-reservation');

// Refesh CRSf
Route::get('/refresh-token', 'PageController@refreshToken')->name('refresh-token');

// Restaurant Attributes
Route::get('/restaurants/{id}', 'PageController@getVendor')->name('restaurants.index');
Route::get('/restaurants', 'PageController@vendors')->name('restaurants');
Route::get('/restaurant-images/{id}', 'VendorController@restaurantImages')->name('restaurant-images');
Route::get('/cuisines', 'VendorController@cuisines')->name('cuisines');
Route::get('/days', 'VendorController@days')->name('days');
Route::get('/features', 'VendorController@features')->name('features');

// Reviews
Route::get('reviews', 'VendorController@getReviews')->middleware(['validate:get-reviews'])->name('get-reviews');

// Voucher
Route::post('/check-voucher', 'PaymentController@checkVoucher')->name('check-voucher');
Route::post('/apply-coupon', 'PaymentController@applyCoupon')->name('apply-coupon');

  // create event-reservation
  Route::post('/create-event-reservation', 'UserController@createEventReservation')->name('create-event-reservation');
  Route::get('paystack-verify-event/{txn_id}', 'PaymentController@paystackVerifyEvent');

//  Laravel Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('impersonate-vendor', 'VendorController@impersonateVendor');
});

// Social Auth
Route::get('/google-redirect', 'SocialAuthController@googleRedirect')->middleware('guest')->name('google-redirect');
Route::get('/google-callback', 'SocialAuthController@googleCallback')->name('google-callback');
Route::get('/facebook-redirect', 'SocialAuthController@facebookRedirect')->name('facebook-redirect');
Route::get('/facebook-callback', 'SocialAuthController@facebookCallback')->name('facebook-callback');

// Payments
Route::post('/start-transaction', 'PaymentController@startTransaction')->name('start-transaction');
Route::get('paystack-verify/{txn_id}', 'PaymentController@paystackVerify');
Route::post('/make-payment', 'PaymentController@makePayment')->name('make-payment');

// Search
Route::get('/search/{type}', 'SearchController@searchVendorsPage')->name('search-page');
Route::get('/search-vendors', 'SearchController@searchVendor')->name('search-vendors');
Route::get('/search-users', 'SearchController@searchUser')->name('search-users');

// Location
Route::get('/countries', 'SearchController@countries')->name('countries');
Route::get('/states', 'SearchController@states')->name('states');
Route::get('/cities', 'SearchController@cities')->name('cities');

// vendor bank details
Route::get('/banks', 'SearchController@banks')->name('banks');
Route::get('/verify-account', 'SearchController@verifyAccount')->name('verify-account');

// Accept Invitation
Route::get('/accept-invitation', 'UserController@acceptInvitation')->name('accept-invitation');

Route::get('/reserve-with-google-json', 'PageController@reserveWithGoogleJSON')->name('reserve-with-google-json');

Route::get('/team-invitations/{invitation}', [VendorController::class, 'acceptInvitation'])
    ->middleware(['auth.vendors.user', 'signed'])
    ->name('team-invitations.accept');

    // dinelist

    Route::get('/dinelist-profile/{slug}', 'PageController@dinelistProfile')->name('dinelist.profile');
    Route::get('/dinelist/{slug}', 'UserController@ViewDinelist')->name('view.dinelist');
    Route::get('/explore-dinelist', 'UserController@ExploreDinelist')->name('explore.dinelist');
    Route::get('/liked-by/{slug}', 'UserController@LikedBy')->name('likedby.dinelist');

//  Inertia User Authenticated routes
   Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Favourite Vendor
       Route::get('/mydinelist', 'PageController@favouriteResturants')->name('restaurants.favourite');

       // dinelist

       Route::post('/dinelist', 'UserController@CreateDinelist')->name('create.dinelist');
       Route::post('/edit-dinelist', 'UserController@EditDinelist')->name('edit.dinelist');
       Route::delete('/dinelist/{id}', 'UserController@DeleteDinelist')->name('delete.dinelist');
       Route::post('/add-vendor', 'UserController@AddVendorToDinelist')->name('add.vendor.dinelist');
       Route::get('/like-dinelist', 'UserController@LikeDinelist')->name('like.dinelist');
       Route::get('/single-dinelist/{id}', 'UserController@SingleDinelist')->name('single.dinelist');
       Route::get('/add-to-dinelist/{id}', 'UserController@AddToDinelist')->name('add.to.dinelist');
       Route::get('/dinelist-get-restaurants', 'UserController@dinelistRestaurants')->name('get.restaurants.dinelist');

       Route::get('/toggle-like', 'VendorController@toggleLike')->name('toggle-like');

       // Search History
       Route::get('/search-history', 'SearchController@searchHistory')->name('search-history');

       // Reservation routes
       Route::post('/update-reservation', 'UserController@updateReservation')->name('update-reservation')->middleware(['validate:update-reservation']);
       Route::get('/cancel-reservation/{id}/{status}', 'UserController@cancelReservation')->name('cancel-reservation');
       Route::get('/reservations', 'UserController@reservations')->name('reservations');
       Route::get('/all-reservations/{name?}', 'UserController@allReservations')->name('all-reservations');
       Route::get('/reservations/{id}', 'UserController@reservation')->name('reservation');
       Route::get('/reservations/edit/{id}', 'UserController@editReservation')->name('reservation.edit');
       Route::post('/invite-guest', 'UserController@inviteGuest')->name('invite-guest')->middleware(['validate:invite-guest']);
       Route::get('/delete-guest/{id}', 'UserController@deleteGuest')->name('delete-guest');
       Route::get('/resend-invitation/{id}', 'UserController@resendInvitation')->name('resend-invitation');
       Route::post('/update-invitation', 'UserController@updateInvitation')->name('update-invitation');
       Route::get('/event-reservations', 'UserController@Eventreservations')->name('event-reservations');
       // Reviews
       Route::post('reviews', 'UserController@createReview')->middleware(['validate:create-review'])->name('reviews');
       Route::delete('reviews/{id}', 'UserController@deleteReview');
   });

// Vendor Routes
Route::prefix('vendors')->name('vendors.')->group(function () {
    Route::middleware(['auth.vendors.user', 'verified'])->group(function () {
        // Logged In Routes
        Route::get('switch-vendor', 'VendorController@switchVendor')->name('switch-vendor');
        Route::get('switch-vendor-accounts', 'VendorController@switchVendorAccounts')->name('switch-vendor-accounts');

        Route::middleware(['vendors.billing'])->group(function () {

            //Vendor Dashboard
            Route::get('/dashboard', 'VendorController@dashboard')->name('dashboard');
            Route::middleware(['auth.vendors'])->group(function () {
               
                Route::post('/enable-service', 'VendorController@enableService')->name('enable-service');

                Route::get('/analytics', 'VendorController@analytics')->name('analytics');

                // vendor order management
                Route::get('/order-management', 'OrderController@index')->name('management.order');

                //Vendor Reservation routes
                // Route::get('/all-reservations', 'VendorController@reservations')->name('reservations');
                Route::get('/reservations', 'VendorController@reservations')->name('reservations');
                Route::get('/reservations/{id?}', 'VendorController@singleReservation')->name('reservation');
                Route::get('/create-reservation', 'VendorController@createReservation')->name('create-reservation');
                Route::post('/make-reservation', 'VendorController@makeReservation')->name('make-reservation')->middleware('validate:vendor-create-reservation');
                Route::post('/update-reservation', 'VendorController@updateReservation')->name('update-reservation');
                Route::post('/toggle-reservation', 'VendorController@toggleReservationStatus')->name('toggle-reservation');

                //Vendor Guests
                Route::get('/guests', 'VendorController@guests')->name('guests');
                Route::post('/guests', 'VendorController@createGuest')->name('create-guest')->middleware('validate:create-guest');
                Route::get('/create-guest', 'VendorController@createGuestPage')->name('create-guest-page');
                Route::get('/guests/{id?}', 'VendorController@editGuestPage')->name('edit-guest-page');
                Route::post('/guests/{id}', 'VendorController@editGuest')->name('edit-guest')->middleware('validate:edit-guest');
                Route::get('/export-guests', 'VendorController@guestsExport')->name('export-guests');
                //  Route::post('/guests/{id}', 'VendorController@editGuest')->name('edit-guest')->middleware('validate:edit-guest');

                Route::get('/transactions', 'VendorController@getVendorTransactions')->name('transactions')->middleware('validate:transactions');

                // Vendor Reviews
                Route::get('/reviews', 'VendorController@reviews')->name('reviews');

                // Vendor Actions
                Route::post('/run-action', 'VendorController@runAction')->name('run-action');

                // Vendor Billing
                Route::get('/change-subscription-plan', 'VendorController@changeSubscriptionPlan')->name('change-subscription-plan')->middleware('validate:vendor-change-subscription-plan');

                Route::get('/tutorial', 'VendorController@tutorial')->name('tutorial');

                // Menus
                Route::get('/menus', 'VendorController@menus')->name('menus');
                Route::post('/menus', 'VendorController@createMenu')->name('create-menu')->middleware('validate:create-menu');
                Route::get('/create-menus', 'VendorController@createMenuPage')->name('create-menu-page');
                Route::get('/menus/{id?}', 'VendorController@editMenuPage')->name('edit-menu-page');
                Route::post('/menus/{id}', 'VendorController@editMenu')->name('edit-menu')->middleware('validate:edit-menu');
                Route::post('/menu-action', 'VendorController@MenuAction')->name('menu-action');
                Route::post('/menu-settings', 'VendorController@menuSettings')->name('menu.settings');

                Route::put('/sorting-menu', 'VendorController@SortingMenu')->name('sorting-menu')->middleware('validate:sorting-menu');
                Route::put('/sorting-menu-items', 'VendorController@SortingMenuItems')->name('sorting-menu-items')->middleware('validate:sorting-menu-items');
                Route::put('/update-menu-item-status', 'VendorController@UpdateMenuItemStatus')->name('update-menu-item-status')->middleware('validate:update-menu-item-status');

                // Menus Categories
                Route::get('/menu-categories', 'VendorController@menuCategories')->name('menu-categories');
                Route::post('/menu-category', 'VendorController@createMenuCategory')->name('create-menu-category')->middleware('validate:create-menu-category');
                Route::get('/create-menu-category', 'VendorController@createMenuCategoryPage')->name('create-menu-category-page');
                Route::get('/menu-category/{id?}', 'VendorController@editMenuCategoryPage')->name('edit-menu-category-page');
                Route::post('/menu-category/{id}', 'VendorController@editMenuCategory')->name('edit-menu-category')->middleware('validate:edit-menu-category');

                // Menus Categories  Items
                Route::get('/menu-category-items', 'VendorController@menuCategoryItems')->name('menu-category-items');
                Route::post('/create-menu-category-item', 'VendorController@createMenuCategoryItem')->name('create-menu-category-item')->middleware('validate:create-menu-category-item');
                Route::get('/create-menu-category-item', 'VendorController@createMenuCategoryItemPage')->name('create-menu-category-item-page');
                Route::get('/menu-category-item/{id?}', 'VendorController@editMenuCategoryItemPage')->name('edit-menu-category-item-page');
                Route::post('/menu-category-item/{id}', 'VendorController@editMenuCategoryItem')->name('edit-menu-category-item')->middleware('validate:edit-menu-category-item');
            });
        });

        Route::middleware(['auth.vendors'])->group(function () {
            //Vendor Billing
            Route::post('/free-trial', 'PaymentController@freeTrial')->name('free-trial')->middleware('validate:free-trial');
            Route::get('/toggle-sub', 'VendorController@toggleSub')->name('toggle-sub');
            Route::get('/billing', 'VendorController@billing')->name('billing');
            Route::get('/pay', 'VendorController@pay')->name('pay');

            //Vendor Settings
            Route::get('/seating-preferences', 'VendorController@seatingpreferences')->name('seating-preferences');
            Route::get('/profile', 'VendorController@profile')->name('profile.show');
            Route::post('/profile', 'VendorController@updateProfile')->name('profile.update');

            // Events
            Route::get('/events', 'VendorController@events')->name('events');
            Route::get('/create-event', 'VendorController@createEventPage')->name('create-event-page');
            Route::get('/event/{id?}', 'VendorController@editEventPage')->name('edit-event-page');
            Route::post('/create-event', 'VendorController@createEvent')->name('create-event')->middleware('validate:create-event');
            Route::post('/event/{id}', 'VendorController@updateEvent')->name('update-event')->middleware('validate:update-event');
            Route::post('/event-action', 'VendorController@EventAction')->name('event-action');
            //Vendor Gallery
            Route::get('/gallery', 'VendorController@gallery')->name('gallery');
            Route::get('/get-images', 'VendorController@getImages')->name('get-images');
            Route::post('/upload-image', 'VendorController@uploadImage')->name('upload-image')->middleware('validate:upload-image');
            Route::post('/upload-images', 'VendorController@uploadImages')->name('upload-images')->middleware('validate:upload-images');
            Route::get('/delete-image/{id}', 'VendorController@deleteImage')->name('delete-image');
            Route::post('/delete-images', 'VendorController@deleteImages')->name('delete-images');
        });
    });

    //Vendor Authentication
    Route::middleware(['vendors.dashboard'])->group(function () {
        Route::get('/', 'VendorController@index')->name('index');
        Route::get('/login', 'VendorController@loginPage')->name('login.index');
        Route::get('/register', 'VendorController@registerPage')->name('register.index');
    });

    Route::get('/logout', 'VendorController@logout')->name('logout');
});

Route::prefix('data')->name('data.')->group(function () {

    // get dinelist

    Route::get('/dinelist', 'UserController@GetALlDinelist')->name('dinelist');
    Route::get('/likedby', 'UserController@likedbyApi')->name('likedby');

    // General unauthenticated routes
    Route::get('/restaurants', 'Api\PageController@restaurants')->name('restaurants');
    Route::get('/events', 'Api\PageController@events')->name('events');
    Route::get('/Allevents', 'Api\PageController@Allevents')->name('Allevents');
    Route::get('/search', 'Api\SearchController@search')->name('search');
    Route::get('/state/{id}', 'Api\SearchController@getState')->name('state');
    Route::get('/city/{id}', 'Api\SearchController@getCity')->name('city');

    // Vendor Routes
    Route::prefix('vendors')->name('vendors.')->group(function () {

        Route::get('/order', 'Api\VendorController@order')->name('orders');
        // Logged In Routes
        Route::get('/reservations', 'Api\VendorController@reservations')->name('reservations');
        Route::get('/guests', 'Api\VendorController@guests')->name('guests');
        Route::get('/menus', 'Api\VendorController@menus')->name('menus');
        Route::get('/menu-categories', 'Api\VendorController@menuCategories')->name('menu-categories');
        Route::get('/menu-category-items', 'Api\VendorController@menuCategoryItems')->name('menu-category-items');
        Route::get('/tutorial', 'Api\VendorController@Tutorial')->name('tutorial');
        Route::get('/events', 'Api\VendorController@events')->name('events');
        Route::get('/transactions', 'Api\VendorController@getVendorTransactions')->name('transactions')->middleware('validate:transactions');

        // metrics
        Route::get('/metrics', 'Api\VendorController@metrics')->name('metrics');
        Route::get('/count-guest-reservations', 'Api\VendorController@countGuestReservations')->name('count-guest-reservations');
    });
});
