<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\GoogleBookingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.')->group(function () {
    Route::post('/paystack-callback', 'PaymentController@paystackCallback');
    Route::post('add-mail', [GeneralController::class, 'addMail']);
    Route::get('maps', [GeneralController::class, 'maps'])->name('maps');

    // Make Reservation
    Route::post('/reservation', 'UserController@makeReservation')->name('make-reservation')->middleware('validate:make-reservation');

    // Auth
    Route::post('login', [AuthController::class, 'login']);

    // Protected Routes
    Route::middleware(['auth:sanctum'])->group(function () {

        // User Routes
        Route::get('/user', [AuthController::class, 'user']);

        // vendor
        Route::post('vendors', 'Api\VendorController@createVendor')->name('create-vendor');

        // Vendor Auth
        Route::get('vendor-accounts', 'Api\VendorController@vendorAccounts')->name('vendor-accounts');
        Route::get('vendor-invitations', 'Api\VendorController@vendorInvitations')->name('vendor-invitations');
        Route::get('accept-invitation', 'Api\VendorController@acceptInvitation')->name('accept-invitation');
        Route::get('leave-current-vendor', 'Api\VendorController@leaveCurrentVendor')->name('leave-current-vendor');

        // Form Data
        Route::get('vendor-form-data', 'Api\VendorController@formData')->name('vendor-form-data');
    });

    // Google Booking Server Protected Routes
    Route::prefix('/google-booking/v3')->middleware(['auth.google.booking'])->group(function () {
        Route::get('/HealthCheck', [GoogleBookingController::class, 'healthCheck']);
        Route::post('/CheckAvailability', [GoogleBookingController::class, 'checkAvailability']);
        Route::post('/CreateBooking', [GoogleBookingController::class, 'createBooking']);
        Route::post('/UpdateBooking', [GoogleBookingController::class, 'updateBooking']);
        Route::post('/GetBookingStatus', [GoogleBookingController::class, 'getBookingStatus']);
        Route::post('/ListBookings', [GoogleBookingController::class, 'listBookings']);
        Route::post('/CheckOrderFulfillability', [GoogleBookingController::class, 'checkOrderFulfillability']);
        Route::post('/CreateOrder', [GoogleBookingController::class, 'createOrder']);
        Route::post('/ListOrders', [GoogleBookingController::class, 'listOrders']);
    });

    // Publicly available Endpoints
    Route::middleware(['auth.clients'])->group(function () {
        // orders
        Route::resource('orders', OrderController::class)->only(['store', 'update', 'destroy']);

        // menus
        Route::get('/menus/{vendor_id}', 'Api\MenuController@menus')->name('get.menus');
        Route::post('/menu', 'Api\MenuController@createMenu')->name('create.menu');
        Route::get('/single-menu/{id}', 'Api\MenuController@singleMenu')->name('single.menu');
        Route::put('/menu/{id}', 'Api\MenuController@updateMenu')->name('update.menu');
        Route::delete('/menu/{id}', 'Api\MenuController@deleteMenu')->name('delete-menu');

        // categories
        Route::get('/categories/{vendor_id}', 'Api\MenuController@getCategories')->name('get.categories');
        Route::post('/category', 'Api\MenuController@createCategory')->name('create.category');
        Route::get('/single-category/{id}', 'Api\MenuController@singleCategory')->name('single.category');
        Route::put('/category/{id}', 'Api\MenuController@updateCategory')->name('update.category');
        Route::delete('/category/{id}', 'Api\MenuController@deleteCategory')->name('delete.category');

        // items
        Route::get('/items/{vendor_id}', 'Api\MenuController@getItems')->name('get.items');
        Route::post('/item', 'Api\MenuController@createItem')->name('create.item');
        Route::get('/single-item/{id}', 'Api\MenuController@singleItem')->name('single.item');
        Route::put('/item/{id}', 'Api\MenuController@updateItem')->name('update.item');
        Route::delete('/item/{id}', 'Api\MenuController@deleteItem')->name('delete.item');

        Route::prefix('/vendors')->group(function () {
            Route::get('/', [VendorController::class, 'index']);
            Route::get('/{vendor}', [VendorController::class, 'show']);
        });
    });
});
