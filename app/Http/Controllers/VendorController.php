<?php

namespace App\Http\Controllers;

use App\Export\GuestExport;
use App\Exports\GuestExport as ExportsGuestExport;
use App\Helpers\Helper;
use App\Jobs\ProcessBulkSms;
use App\Jobs\SetupMira;
use App\Mail\MailTemplate;
use App\Models\Cuisine;
use App\Models\Day;
use App\Models\DayVendor;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Guest;
use App\Models\Image;
use App\Models\Like;
use App\Models\Membership;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use App\Models\MenuSetting;
use App\Models\PasswordReset;
use App\Models\Plan;
use App\Models\PlanFrequency;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\SeatingPreference;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Services\AllServices\Admin;
use App\Services\AllServices\FileService;
use App\Services\AllServices\General;
use App\Services\AllServices\Mira;
use App\Services\PaymentMethods\Paystack;
use Carbon\Carbon;
use Google\Service\CloudSearch\Id;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\TeamInvitation;
use Maatwebsite\Excel\Facades\Excel;

class VendorController extends Controller
{
    public function index()
    {
        $data = [
            'appName' => config('app.name'),
            'page' => 'index',
            'title' => 'Vendor Portal',
            'description' => 'Manage your restaurant, reservations and more',
        ];

        return Inertia::render('Vendor/Index', $data);
    }

    public function loginPage()
    {
        session(['auth_type' => 'vendor']);

        return Inertia::render('Vendor/Auth/Login');
    }

    public function registerPage()
    {
        session(['auth_type' => 'vendor']);

        return Inertia::render('Vendor/Auth/Register');
    }

    public function forgotPasswordPage()
    {
        return Inertia::render('Vendor/Auth/ForgotPassword');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'email|required|max:255|exists:admins,email',
        ]);

        return Inertia::render('Vendor/Auth/ForgotPassword', [
            'status' => 'We have emailed your password reset link!',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('vendors.login.index');
    }

    public function dashboard()
    {
        return Inertia::render('Vendor/Dashboard');
    }

    public function profile()
    {
        $vendor = session('current_vendor');
        $vendor->load('days');
        $data['vendor'] = $vendor;

        if (session('fromImageUpload')) {
            session()->flash('flash.banner', 'Image(s) Uploaded!');
            session()->flash('flash.bannerStyle', 'success');
            session()->forget('fromImageUpload');
        }

        return Inertia::render('Vendor/Profile/Show', $data);
    }

    public function updateProfile(Request $request)
    {
        $vendor = Vendor::find($request->id);

        $request->validate([
            'id' => 'required|exists:vendors,id',
            'company_name' => 'required|min:1',
            'min_reservation_amount' => 'numeric|min:1000',
            'company_address' => 'required|min:1',
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone_number' => 'required|min:1',
            // 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($vendor->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'menu_pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($vendor->paystack_code === null && $request->account_number != null) {
            $res = Paystack::createSubAccount($request->company_name, $request->bank_code, $request->account_number);
            $vendor->paystack_code = $res->data->subaccount_code;
        }

        if ($request->company_name != $vendor->company_name) {
            $vendor->slug = Str::slug($vendor->company_name, '-');
            $vendor->save();
        }

        $vendor->company_name = $request->company_name;
        $vendor->company_address = $request->company_address;
        $vendor->first_name = $request->first_name;
        $vendor->last_name = $request->last_name;
        $vendor->phone_number = $request->phone_number;
        $vendor->company_address = $request->company_address;
        $vendor->party_size = $request->party_size;
        $vendor->open = $request->open;
        $vendor->open_time = $request->open_time;
        $vendor->close_time = $request->close_time;
        $vendor->is_same_time = $request->is_same_time;
        $vendor->description = $request->description;
        $vendor->company_email = $request->company_email;
        $vendor->company_phone = $request->company_phone;
        $vendor->cancellation_policy = $request->cancellation_policy;
        $vendor->menu_link = $request->menu_link;
        $vendor->menu_highlights = $request->menu_highlights;
        $vendor->whatsapp_number = $request->whatsapp_number;
        $vendor->facebook_link = $request->facebook_link;
        $vendor->instagram_link = $request->instagram_link;
        $vendor->twitter_link = $request->twitter_link;
        $vendor->youtube_link = $request->youtube_link;
        $vendor->linkedin_link = $request->linkedin_link;
        $vendor->country_id = $request->country_id;
        $vendor->state_id = $request->state_id;
        $vendor->city_id = $request->city_id;
        $vendor->attach_menu_pdf = $request->attach_menu_pdf;
        $vendor->dress_code = $request->dress_code;
        $vendor->average_menu_price_currency = $request->average_menu_price_currency;
        $vendor->average_menu_price = $request->average_menu_price;
        $vendor->latitude = $request->lat;
        $vendor->longitude = $request->lng;
        $vendor->bank_code = $request->bank_code;
        $vendor->account_number = $request->account_number;
        $vendor->account_name = $request->account_name;
        $vendor->reservation_deposit_required = $request->reservation_deposit_required;
        $vendor->accept_reservation_deposit = $request->accept_reservation_deposit;
        $vendor->min_reservation_deposit = $request->min_reservation_deposit;
        $vendor->reservation_currency = $request->reservation_currency;
        $vendor->use_external_menu_page = $request->use_external_menu_page;
        $vendor->save();

        session(['current_vendor' => $vendor]);

        $team = Team::find($vendor->id);
        if ($team) {
            $team->name = $request->company_name;
            $team->save();
        }

        $vendor->cuisines()->detach();
        foreach ($request->cuisines as $key => $cuisineData) {
            $cuisineData = (object) $cuisineData;
            if ($cuisineData->vendor_attached == true) {
                $vendor->cuisines()->attach($cuisineData->id);
            }
        }

        $vendor->days()->detach();
        if ($request->days) {
            foreach ($request->days as $key => $dayData) {
                $dayData = (object) $dayData;
                if ($dayData->vendor_attached == true) {
                    $vendor->days()->attach($dayData->id, ['open_time' => isset($dayData->open_time) ? $dayData->open_time : null, 'close_time' => isset($dayData->close_time) ? $dayData->close_time : null]);
                }
            }
        }

        $vendor->features()->detach();
        foreach ($request->features as $key => $featureData) {
            $featureData = (object) $featureData;
            if ($featureData->vendor_attached == true) {
                $vendor->features()->attach($featureData->id);
            }
        }

        $vendor->seatingPreferences()->get()->each->delete();
        if ($request->seating_preferences) {
            foreach ($request->seating_preferences as $key => $seating_preferenceData) {
                $seating_preferenceData = (object) $seating_preferenceData;
                $sp = new SeatingPreference;
                $sp->name = $seating_preferenceData->name;
                $sp->vendor_id = $vendor->id;
                $sp->required = $seating_preferenceData->required;
                $sp->save();
            }
        }

        if ($request->file('menu_pdf')) {
            $path = FileService::storeFile($request->file('menu_pdf'), 'menu_pdfs', true);
            $vendor->menu_pdf = $path;
            $vendor->save();
        }

        // return Helper::apiRes("vendor updated", $vendor);
        $data['vendor'] = $vendor;
        $data['status'] = 'Vendor Account Updated';
        // dd($vendor);
        return Inertia::render('Vendor/Profile/Show', $data);
    }

    public function gallery(Request $request)
    {
        $vendor = session('current_vendor');
        $data['vendor'] = $vendor;
        if (session('fromImageUpload')) {
            session()->flash('flash.banner', 'Image(s) Uploaded!');
            session()->flash('flash.bannerStyle', 'success');
            session()->forget('fromImageUpload');
        }

        return Inertia::render('Vendor/Gallery', $data);
    }

    public function toggleReservationStatus(Request $request)
    {
        // dd($request->all());
        $ids = $request->ids;
        $status = $request->action;

        if ($request->allMatching) {
            $vendor = session('current_vendor');

            $filters = $request->filters;
            $filters['type'] = $request->type;
            $filters = (object) $filters;

            $query = General::getReservations($vendor, $filters, $request->type);
            $reservations = $query->get();

            foreach ($reservations as $key => $reservation_data) {
                $reservation = Reservation::find($reservation_data->id);
                if ($reservation && !$reservation->past) {
                    try {
                        General::updateReservationStatus($reservation, $status, 'admin');
                    } catch (\Throwable $th) {
                        // throw $th;
                        Log::error($th);
                    }
                }
            }
        } else {
            foreach ($ids as $key => $id) {
                $reservation = Reservation::find($id);
                if ($reservation && !$reservation->past) {
                    try {
                        General::updateReservationStatus($reservation, $status, 'admin');
                    } catch (\Throwable $th) {
                        // throw $th;
                        Log::error($th);
                    }
                }
            }
        }

        session()->flash('flash.banner', 'Reservation(s) Updated!');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->back();
    }

    public function reservations(Request $request)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'status', 'reservation_start_date', 'reservation_end_date', 'type', 'view', 'date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        if ($filters->type) {
            $title = $filters->type . ' Reservations';
        } else {
            $title = 'Reservations';
        }

        if ($filters->view == 'calendar') {
            if (!$filters->date) {
                $filters->date = now()->toDateString();
                request()->merge(['date' => $filters->date]);
            }
        }

        return Inertia::render('Vendor/Reservations/Index', [
            'page' => 'vendor-reservations',
            'title' => $title,
            'filters' => request()->all($query_params),
        ]);
    }

    public function uploadImage(Request $request)
    {
        $vendor = session('current_vendor');

        try {
            $path = FileService::storeFile($request->file('image'), 'vendor_images');
        } catch (\Throwable $th) {
            throw $th;
        }

        if ($request->type == 'profilePhoto') {
            $vendor->profile_photo_path = $path;
        }

        if ($request->type == 'bannerPhoto') {
            $vendor->banner = $path;
        }

        if ($request->type == 'galleryPhoto') {
            $image = new Image();
            $image->vendor_id = $vendor->id;
            $image->path = $path;
            $image->save();
        }

        $vendor->save();

        session(['fromImageUpload' => true]);

        return Helper::apiRes('Image Uploaded');
    }

    public function uploadImages(Request $request)
    {
        $vendor = session('current_vendor');
        foreach ($request->images as $key => $img) {
            // code...
            try {
                $path = FileService::storeFile($img, 'vendor_images');
            } catch (\Throwable $th) {
                throw $th;
            }

            $image = new Image();
            $image->vendor_id = $vendor->id;
            $image->path = $path;
            $image->save();
        }

        session(['fromImageUpload' => true]);

        return Helper::apiRes('Images Uploaded');
    }

    public function getImages()
    {
        $vendor = session('current_vendor');

        return Helper::apiRes('Images', Image::where('vendor_id', $vendor->id)->orderBy('id', 'desc')->get());
    }

    public function deleteImage($id)
    {
        $image = Image::find($id);

        if ($image) {
            try {
                FileService::deleteFile($image->path);
            } catch (\Throwable $th) {
                return Helper::apiRes($th->getMessage(), [], false, 500);
            }
            $image->delete();
        }

        return Helper::apiRes('Image Deleted');
    }

    public function deleteImages(Request $request)
    {

        // dd($request->ids);
        foreach ($request->ids as $key => $id) {
            $image = Image::find($id);

            if ($image) {
                try {
                    FileService::deleteFile($image->path);
                } catch (\Throwable $th) {
                    return Helper::apiRes($th->getMessage(), [], false, 500);
                }
                $image->delete();
            }
        }

        return Helper::apiRes('Image Deleted');
    }

    public function restaurantImages($id)
    {
        $vendor = Vendor::find($id);
        $images = Image::where('vendor_id', $vendor->id)->orderBy('id', 'desc')->get();

        $data['image_links'] = [];

        foreach ($images as $key => $image) {
            array_push($data['image_links'], $image->image_url);
        }

        return Helper::apiRes('Images', $data);
    }

    public function cuisines(Request $request)
    {
        $vendor = null;

        if ($request->query('vendor_id')) {
            $vendor = Vendor::find($request->query('vendor_id'));
            if ($vendor) {
                if ($request->query('only_ids')) {
                    $cuisines = $vendor->cuisines ? $vendor->cuisines->pluck('id')->toArray() : [];
                } else {
                    $cuisines = $vendor->cuisines;
                }
            }
        }

        $data['cuisines'] = $vendor ? $cuisines : Cuisine::all();

        return Helper::apiRes('Cuisines', $data);
    }

    public function singleReservation(Request $request)
    {
        $reservation = Reservation::where('id', $request->id)->with(['user', 'guest', 'transaction'])->first();

        if (!$reservation) {
            abort(404, 'reservation not found');
        }

        $vendor = $reservation->vendor;
        $vendor->load('seatingPreferences', 'days');

        $data = [
            'reservation' => $reservation,
            'vendor' => $reservation->vendor,
            'page' => 'vendor-reservations',
            'title' => 'Reservation',
        ];

        return Inertia::render('Vendor/Reservations/Detail', $data);
    }

    public function days(Request $request)
    {
        $vendor = null;

        if ($request->query('vendor_id')) {
            $vendor = Vendor::find($request->query('vendor_id'));
            if ($vendor) {
                if ($request->query('only_ids')) {
                    $days = $vendor->days ? $vendor->days->pluck('id')->toArray() : [];
                } else {
                    $days = DayVendor::where('vendor_id', $vendor->id)->with('day')->get();
                }
            }
        }

        $data['days'] = $vendor ? $days : Day::all();

        return Helper::apiRes('days', $data);
    }

    public function features(Request $request)
    {
        $vendor = null;

        if ($request->query('vendor_id')) {
            $vendor = Vendor::find($request->query('vendor_id'));
            if ($vendor) {
                if ($request->query('only_ids')) {
                    $features = $vendor->features ? $vendor->features->pluck('id')->toArray() : [];
                } else {
                    $features = $vendor->features;
                }
            }
        }

        $data['features'] = $vendor ? $features : Feature::all();

        return Helper::apiRes('features', $data);
    }

    public function getReviews(Request $request)
    {
        $user = Auth::user();
        $vendor_id = $request->query('vendor_id');
        $reviews = [];

        if ($vendor_id) {
            $reviews = Review::where('vendor_id', $request->vendor_id)->with(['user'])->orderBy('id', 'desc')->get();
        }

        $data['reviews'] = $reviews;
        $data['user_review'] = $user ? $reviews->where('user_id', $user->id)->first() : null;

        return Helper::apiRes('Reviews', $data);
    }

    public function billing(Request $request)
    {
        $vendor = session('current_vendor');

        $vendor->load('plan', 'planFrequency');
        $data['vendor'] = $vendor;
        $data['subscriptions'] = Subscription::where('vendor_id', $vendor->id)->with('plan', 'planFrequency')->orderBy('created_at', 'desc')->get();

        $subscription = Subscription::where('vendor_id', $vendor->id)
            ->where('active', true)
            ->with('plan', function ($query) {
                $query->with('planFeatures');
            })
            ->first();

        if ($subscription) {
            // code...
            $subscription->load('planFrequency');
            $data['subscription'] = $subscription;
            $data['subscription']['card'] = $vendor->card;
            // $data['subscription']['start'] = Carbon::parse($subscription->plan_start)->format("jS F Y");
            // $data['subscription']['end'] = Carbon::parse($subscription->plan_end)->format("jS F Y");
        }

        $data['plans'] = Plan::where('type', 'vendor')->with('planFeatures', 'planFrequencies')->get();
        $data['billing_msg'] = session('billing_msg');
        $data['billing_msg_status'] = session('billing_msg_status');

        return Inertia::render('Vendor/Billing/Index', $data);
    }

    public function pay(Request $request)
    {
        $vendor = session('current_vendor');
        $type = $request->query('type');
        $plan_id = $request->query('plan_id');
        $freq_id = $request->query('plan_frequency_id');

        $data['type'] = $type;

        if ($type != 'subscription' && $type != 'new_card') {
            abort(404);
        }

        if ($type == 'subscription') {
            if ($plan_id && $freq_id) {
                // code...
                $plan = Plan::find($plan_id);
                $plan_frequency = PlanFrequency::find($freq_id);

                if (!$plan || !$plan_frequency) {
                    abort(404);
                }

                $data['plan'] = $plan;
                $data['plan']['frequency'] = $plan_frequency;
            } else {
                abort(404);
            }
        }

        $data['vendor'] = $vendor;
        $data['plans'] = Plan::with('planFeatures', 'planFrequencies')->get();
        $data['page'] = 'payment';
        $data['paystackkey'] = config('services.paystack.mode') == 'live' ? config('services.paystack.live_pk') : config('services.paystack.test_pk');
        $data['stripekey'] = config('services.stripe.mode') == 'live' ? config('services.stripe.key') : config('services.stripe.test_key');

        return Inertia::render('Vendor/Billing/Pay', $data);
    }

    public function resetPasswordPage(Request $request)
    {
        // dd($request->query('email'),$request->query('token'));
        $check = PasswordReset::where('email', $request->query('email'))->where('token', $request->query('token'))->where('type', 'admin')->first();
        if (!$check) {
            abort(404);
        }

        return Inertia::render('Vendor/Auth/ResetPassword', [
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'email|required|max:255|exists:admins,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $check = PasswordReset::where('email', $request->email)->where('type', 'admin')->first();
        if ($check) {
            $check->delete();
        }

        return Inertia::render('Vendor/Auth/Login', [
            'status' => 'Your password has been reset!',
        ]);
    }

    public function impersonateVendor(Request $request)
    {
        $vendor = Vendor::find($request->vendor_id);
        $team = Team::find($request->vendor_id);
        $admin = Auth::user();

        $is_admin = $admin->is_admin ?: session('admin_logged_in');

        if (!$vendor || !$is_admin || !$team) {
            abort(403);
        }

        session(['admin_logged_in' => true]);

        Auth::login($vendor->user);
        session(['current_vendor' => $vendor]);
        if (!$request->user()->switchTeam($team)) {
            abort(403);
        }

        return redirect()->route('vendors.dashboard');
    }

    public function getVendorData($id)
    {
        $vendor = Vendor::where('id', $id)->with(['cuisines', 'reviews'])->first();
        if (!$vendor) {
            return Helper::apiRes('vendor not found', [], false);
        }

        return Helper::apiRes('Vendor', $vendor);
    }

    public function toggleLike(Request $request)
    {
        $vendor_id = $request->query('vendor_id');
        if (!$vendor_id) {
            return;
        }

        $user = Auth::user();

        $like = Like::where('vendor_id', $vendor_id)->where('user_id', $user->id)->first();

        if ($like) {
            // dd("exist",$like);
            $like->delete();
        } else {
            $like = new Like();
            $like->vendor_id = $vendor_id;
            $like->user_id = $user->id;
            $like->save();
            // dd("created",$like);
        }

        return Helper::apiRes('Toggled the Like');
    }

    public function toggleSub(Request $request)
    {
        $action = $request->query('action');

        if (!$action || !in_array($request->query('action'), ['cancel', 'renew'])) {
            abort(404);
        }

        $vendor = session('current_vendor');
        $vendor->renew_subscription = $action == 'cancel' ? false : true;
        $vendor->save();

        return redirect()->back()->with(['success' => 'Subscription ' . $action . 'ed']);
    }

    public function guests(Request $request)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'approved', 'birthday', 'birthday_date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        return Inertia::render('Vendor/Guests/Index', [
            'page' => 'vendor-guest-book',
            'title' => 'Guests Book',
            'filters' => request()->all($query_params),
        ]);
    }

    public function makeReservation(Request $request)
    {
        $vendor = session('current_vendor');
        $newGuest = (object) $request->guest;

        if (!$request->user_id) {
            if (!$newGuest->first_name) {
                return Helper::apiRes('Guest firsst_name is required', [], false, 401);
            }
            if ($newGuest->email) {
                if (General::isBlocked($vendor->blockLists, $newGuest->email)) {
                    return Helper::apiRes("<p>$newGuest->email is blocked from making Reservations to $vendor->company_name </p> <p  style='margin-top:15px'>Please check in your Guest Book to unblock</p> ", [], false, 401);
                }
            }
        } else {
            $user = User::find($request->user_id);
            if ($user) {
                if (General::isBlocked($vendor->blockLists, $user->email)) {
                    return Helper::apiRes("<p>$user->email is Blocked from making Reservations to $vendor->company_name</p><p  style='margin-top:15px'>Please check in your Guest Book to unblock</p> ", [], false, 401);
                }
            }
        }

        if (!$newGuest->first_name) {
            $request->guest = null;
        }

        try {
            $reservation = Admin::createReservation($vendor->id, $request->party_size, $request->date, $request->time, $request->user_id, $request->guest_id, $request->created_by_vendor, $request->seating_preferences, $request->phone, $request->note, $request->source, $request->guest);
        } catch (\Throwable $th) {
            throw $th;

            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Reservation Successfull', $reservation);
    }

    public function createReservation(Request $request)
    {
        $vendor = session('current_vendor');
        $data['vendor'] = $vendor;
        $vendor->load('seatingPreferences', 'blockLists', 'days');

        return Inertia::render('Vendor/Reservations/Create', $data);
    }

    public function seatingpreferences(Request $request)
    {
        $vendor = session('current_vendor');
        $data['seating_preferences'] = $vendor->seatingPreferences;

        return Helper::apiRes('Seating Preferences', $data);
    }

    public function createGuestPage(Request $request)
    {
        $vendor = session('current_vendor');
        $vendor->load('seatingPreferences', 'days');
        $data['vendor'] = $vendor;

        return Inertia::render('Vendor/Guests/Create', $data);
    }

    public function createGuest(Request $request)
    {
        $vendor = session('current_vendor');

        try {
            $guest = Admin::createGuest($vendor->id, $request->first_name, $request->last_name, $request->email, $request->phone, $request->birthday, $request->seating_preferences, $request->user_id, $request->general_notes, $request->food_drink_preferences, $request->source);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Guest Created Successfully', $guest);
    }

    public function editGuestPage(Request $request)
    {
        $guest = Guest::find($request->id);

        if (!$guest) {
            abort(404);
        }

        $vendor = session('current_vendor');
        $vendor->load('seatingPreferences', 'days');
        $data['vendor'] = $vendor;
        $data['guest'] = $guest;

        $filter = ['guest_id' => $guest->id];
        $filter = (object) $filter;

        $reservations = General::getReservations($vendor, $filter, null)->with(['user', 'guest']);
        $data['reservations'] = $reservations->get();

        return Inertia::render('Vendor/Guests/Edit', $data);
    }

    public function editGuest(Request $request)
    {
        $guest = Guest::find($request->id);

        try {
            $guest = Admin::updateGuest($guest, $request->first_name, $request->last_name, $request->email, $request->phone, $request->birthday, $request->seating_preferences, $request->user_id, $request->general_notes, $request->food_drink_preferences, $request->source);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Guest Updated Successfully', $guest);
    }

    public function updateReservation(Request $request)
    {
        $reservation = Reservation::find($request->id);

        try {
            $reservation = Admin::updateReservation($reservation, $request->party_size, $request->date, $request->time, $request->seating_preferences, $request->phone, $request->note, $request->source);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Reservation Updated Successfully', $reservation);
    }

    public function reviews(Request $request)
    {
        $vendor = session('current_vendor');
        $query = Review::where('vendor_id', $vendor->id)->with('user')->orderBy('created_at', 'desc');

        return Inertia::render('Vendor/Reviews/Index', [
            'models' => $query->paginate()->withQueryString(),
            'vendor' => $vendor,
            'title' => 'Reviews',
        ]);
    }

    public function runAction(Request $request)
    {
        $ids = $request->ids;
        $withoutAsync = $request->query('without_async');
        $action = $request->action;
        $action_model = $request->action_model;
        $banner = null;
        if ($request->file('banner')) {
            try {
                $path = FileService::storeFile($request->file('banner'), 'mail_banners');
            } catch (\Throwable $th) {
                throw $th;
            }
            $banner = Storage::cloud()->url($path);
        }

        if ($request->allMatching == 'true') {
            $vendor = session('current_vendor');

            $filters = (array) json_decode($request->filters);
            $filters['type'] = $request->type;
            $filters = (object) $filters;
            $models = [];

            if ($action_model == 'reservation') {
                $query = General::getReservations($vendor, $filters, $request->type);
                $models = $query->get();
            }

            if ($action_model == 'guest') {
                $models = Guest::where('vendor_id', $vendor->id)->get();
            }

            foreach ($models as $key => $model) {
                if ($model) {
                    $vendor = $model->vendor;
                    if ($vendor) {
                        // code...
                        if ($action_model == 'reservation') {
                            $email = $model->user ? $model->user->email : $model->guest->email;
                            $first_name = $model->user ? $model->user->first_name : $model->guest->first_name;
                        }

                        if ($action_model == 'guest') {
                            $email = $model->email;
                            $first_name = $model->first_name;
                        }

                        if ($action == 'email_users') {
                            Mail::to($email)->queue(new MailTemplate($first_name, $vendor, $request->subject, $banner, $request->message, [
                                'user_id' => $model->user ? $model->user->id : null,
                                'guest_id' => $model->guest ? $model->guest->id : null,
                                'vendor_id' => $vendor->id,
                                'route' => 'email',
                                'type' => 'email_users',
                                'content' => $request->subject . "\n\n" . $request->message,
                                'action_by' => 'vendor',
                            ]));
                        }

                        if ($action == 'text_users') {
                            ProcessBulkSms::dispatchAfterResponse($model->phone, $first_name, $request->message, $vendor->company_name, [
                                'user_id' => $model->user ? $model->user->id : null,
                                'guest_id' => $model->guest ? $model->guest->id : null,
                                'vendor_id' => $vendor->id,
                                'route' => 'sms',
                                'type' => 'text_users',
                                'content' => $request->message,
                                'action_by' => 'vendor',
                            ]);
                        }

                        if ($action == 'block') {
                            General::block($vendor, $model->email);
                        }

                        if ($action == 'un-block') {
                            General::unBlock($vendor, $model->email);
                        }
                    }
                }
            }
        } else {
            $ids = is_array($ids) ? $ids : explode(',', $ids . ',');

            foreach ($ids as $key => $id) {
                $model = null;
                $email = null;
                $first_name = null;

                if ($id) {
                    if ($id != '') {
                        if ($action_model == 'reservation') {
                            $model = Reservation::find($id);
                            if ($model) {
                                $email = $model->user ? $model->user->email : $model->guest->email;
                                $first_name = $model->user ? $model->user->first_name : $model->guest->first_name;
                            }
                        }

                        if ($action_model == 'guest') {
                            $model = Guest::find($id);
                            if ($model) {
                                $email = $model->email;
                                $first_name = $model->first_name;
                            }
                        }
                    }
                }

                if ($model) {
                    $vendor = $model->vendor;
                    if ($vendor) {
                        // code...
                        if ($action == 'email_users') {
                            if ($email) {
                                Mail::to($email)->queue(new MailTemplate($first_name, $vendor, $request->subject, $banner, $request->message, [
                                    'user_id' => $model->user ? $model->user->id : null,
                                    'guest_id' => $model->guest ? $model->guest->id : null,
                                    'vendor_id' => $vendor->id,
                                    'route' => 'email',
                                    'type' => 'email_users',
                                    'content' => $request->subject . "\n\n" . $request->message,
                                    'action_by' => 'vendor',
                                ]));
                            }
                        }

                        if ($action == 'text_users') {
                            if ($model->phone) {
                                ProcessBulkSms::dispatchAfterResponse($model->phone, $first_name, $request->message, $vendor->company_name, [
                                    'user_id' => $model->user ? $model->user->id : null,
                                    'guest_id' => $model->guest ? $model->guest->id : null,
                                    'vendor_id' => $vendor->id,
                                    'route' => 'sms',
                                    'type' => 'text_users',
                                    'content' => $request->message,
                                    'action_by' => 'vendor',
                                ]);
                            }
                        }

                        if ($action == 'block') {
                            if ($model->email) {
                                General::block($vendor, $model->email);
                            }
                        }

                        if ($action == 'un-block') {
                            if ($model->email) {
                                General::unBlock($vendor, $model->email);
                            }
                        }
                    }
                }
            }
        }

        $res = 'Action Ran Succesfully';
        if ($action == 'email_users') {
            $res = 'Email(s) Sent!';
        }

        if ($action == 'text_users') {
            $res = 'Text(s) Sent!';
        }

        if ($action == 'remove_admin') {
            $res = 'Admins Removed!';
        }

        if ($action == 'block' || $action == 'un-block') {
            session()->flash('flash.banner', "$action_model(s) $action" . 'ed!');
            session()->flash('flash.bannerStyle', 'success');
        }

        if ($action == 'remove_admin') {
            session()->flash('flash.banner', 'Admin(s) Removeded!');
            session()->flash('flash.bannerStyle', 'success');
        }

        return $withoutAsync ? redirect()->back() : Helper::apiRes($res);
    }

    public function changeSubscriptionPlan(Request $request)
    {
        $vendor = session('current_vendor');
        $vendor->plan_id = $request->plan_id;
        $vendor->plan_frequency_id = $request->plan_frequency_id;
        $vendor->save();

        session()->flash('flash.banner', 'Next Subscription Plan Updated');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->back();
    }

    public function analytics()
    {
        return Inertia::render('Vendor/Analytics');
    }

    public function menus(Request $request)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;
        $vendor = session('current_vendor');
        $setting = MenuSetting::where('vendor_id', $vendor->id)->first();

        return Inertia::render('Vendor/Menus/Index', [
            'page' => 'vendor-menus',
            'title' => 'Menus Page',
            'filters' => request()->all($query_params),
            'menu_setting' => $setting,
        ]);
    }

    public function createMenuPage(Request $request)
    {
        $vendor = session('current_vendor');
        $data['vendor'] = $vendor;

        return Inertia::render('Vendor/Menus/Create', $data);
    }

    public function createMenu(Request $request)
    {
        $vendor = session('current_vendor');

        try {
            $menu = Admin::createMenu($vendor->id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Menu Created Successfully', $menu);
    }

    public function editMenuPage(Request $request)
    {
        $menu = Menu::find($request->id);

        if (!$menu) {
            abort(404);
        }
        $vendor = session('current_vendor');
        $data['categories'] = MenuCategory::whereHas('menus', function ($query) use ($request) {
            $query->where('menu_id', $request->id);
        })->where('vendor_id', $vendor->id)->get();
        $data['vendor'] = $vendor;
        $data['menu'] = $menu;

        return Inertia::render('Vendor/Menus/Edit', $data);
    }

    public function editMenu(Request $request)
    {
        $vendor = session('current_vendor');
        $menu = Menu::find($request->id);

        try {
            $menu = Admin::updateMenu($menu, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Menu Updated Successfully', $menu);
    }

    // menuCategories

    public function menuCategories(Request $request)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'menu_id'];
        $vendor = session('current_vendor');
        $filters = request()->all($query_params);
        $filters = (object) $filters;
        $menus = Menu::where('vendor_id', $vendor->id)->get();

        return Inertia::render('Vendor/MenuCategories/Index', [
            'page' => 'vendor-menu-categories',
            'title' => 'Menu Categories Page',
            'menus' => $menus,
            'filters' => request()->all($query_params),
        ]);
    }

    public function createMenuCategoryPage(Request $request)
    {
        $vendor = session('current_vendor');
        $menus = Menu::where('vendor_id', $vendor->id)->get();
        $data['vendor'] = $vendor;
        $data['menus'] = $menus;

        return Inertia::render('Vendor/MenuCategories/Create', $data);
    }

    public function createMenuCategory(Request $request)
    {
        $vendor = session('current_vendor');

        try {
            $menu = Admin::createMenuCategory($vendor->id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Category Created Successfully', $menu);
    }

    public function editMenuCategoryPage(Request $request)
    {
        $category = MenuCategory::find($request->id);

        if (!$category) {
            abort(404);
        }
        $vendor = session('current_vendor');
        $menus = Menu::where('vendor_id', $vendor->id)->get();
        $data['vendor'] = $vendor;
        $data['category'] = $category;
        $data['selectedMenus'] = $category->menus;
        $data['menus'] = $menus;

        return Inertia::render('Vendor/MenuCategories/Edit', $data);
    }

    public function editMenuCategory(Request $request)
    {
        $vendor = session('current_vendor');
        $category = MenuCategory::find($request->id);

        try {
            $menu = Admin::updateMenuCategory($category, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Category Updated Successfully');
    }

    // menuCategoryItems

    public function menuCategoryItems(Request $request)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'category_id'];
        $vendor = session('current_vendor');
        $filters = request()->all($query_params);
        $filters = (object) $filters;
        $menuCategories = MenuCategory::where('vendor_id', $vendor->id)->get();

        return Inertia::render('Vendor/MenuCategoryItems/Index', [
            'page' => 'vendor-menu-category-items',
            'title' => 'MenuItems Page',
            'menuCategories' => $menuCategories,
            'filters' => request()->all($query_params),
        ]);
    }

    // createMenuCategoryItemPage

    public function createMenuCategoryItemPage(Request $request)
    {
        $vendor = session('current_vendor');
        $categories = MenuCategory::where('vendor_id', $vendor->id)->get();
        $data['vendor'] = $vendor;
        $data['categories'] = $categories;

        return Inertia::render('Vendor/MenuCategoryItems/Create', $data);
    }

    // createMenuCategoryItem

    public function createMenuCategoryItem(Request $request)
    {
        $vendor = session('current_vendor');

        try {
            $item = Admin::createMenuCategoryItem($vendor->id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Item Created Successfully', $item);
    }

    public function editMenuCategoryItemPage(Request $request)
    {
        $item = MenuCategoryItem::find($request->id);

        if (!$item) {
            abort(404);
        }
        $vendor = session('current_vendor');
        $categories = MenuCategory::where('vendor_id', $vendor->id)->get();
        $data['vendor'] = $vendor;
        $data['item'] = $item;
        $data['selectedCategories'] = $item->categories;
        $data['categories'] = $categories;

        return Inertia::render('Vendor/MenuCategoryItems/Edit', $data);
    }

    // editMenuCategoryItem

    public function editMenuCategoryItem(Request $request)
    {
        $vendor = session('current_vendor');
        $item = MenuCategoryItem::find($request->id);

        try {
            $item = Admin::updateMenuCategoryItem($item, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('Item Updated Successfully', $item);
    }

    public function MenuAction(Request $request)
    {
        $ids = $request->ids;
        $withoutAsync = $request->query('without_async');
        $action = $request->action;
        $action_model = $request->action_model;

        if ($action == 'delete' && $action_model == 'menu') {
            $delete = Menu::whereIn('id', $ids)->delete();

            if ($delete) {
                session()->flash('flash.banner', 'Menu Deleted Successfully!');

                return   redirect()->back();
            }
        } elseif ($action == 'delete' && $action_model == 'menuCategory') {
            $delete = MenuCategory::whereIn('id', $ids)->delete();

            if ($delete) {
                session()->flash('flash.banner', 'Category Deleted Successfully!');

                return   redirect()->back();
            }
        } elseif ($action == 'delete' && $action_model == 'menuCategoryItem') {
            $delete = MenuCategoryItem::whereIn('id', $ids)->delete();

            if ($delete) {
                session()->flash('flash.banner', 'Item Deleted Successfully!');

                return   redirect()->back();
            }
        }
    }

    public function EventAction(Request $request)
    {
        $vendor = session('current_vendor');
        if (!$vendor) {
            abort(404);
        }

        $ids = $request->ids;

        $delete = Event::whereIn('id', $ids)->delete();

        if ($delete) {
            session()->flash('flash.banner', 'Event Deleted Successfully!');

            return   redirect()->back();
        }
    }

    public function tutorial()
    {
        return Inertia::render(
            'Vendor/Tutorial/Index',
            [
                'page' => 'tutorial',
                'title' => 'Tutorial Page',
            ]
        );
    }

    public function events()
    {
        return Inertia::render(
            'Vendor/Events/Index',
            [
                'page' => 'events',
                'title' => 'Events Page',
            ]
        );
    }

    public function createEventPage()
    {
        return Inertia::render(
            'Vendor/Events/Create',
            [
                'page' => 'createEvent',
                'title' => 'Create Events Page',
            ]
        );
    }

    public function editEventPage(Request $request)
    {
        $event = Event::with(['images', 'reservations' => function ($query) {
            $query->with(['user']);
        }])->find($request->id);

        $vendor = session('current_vendor');
        $data['event'] = $event;
        $data['vendor'] = $vendor;
        $data['title'] = 'Edit Events Page';

        return Inertia::render('Vendor/Events/Edit', $data);
    }

    public function handleEventImage($images)
    {
        $arr_images = [];
        foreach ($images as $key => $img) {
            try {
                $path = FileService::storeFile($img, 'event_images');
            } catch (\Throwable $th) {
                throw $th;
            }
            array_push($arr_images, $path);
        }

        return  $arr_images;
    }

    public function createEvent(Request $request)
    {
        $vendor = session('current_vendor');

        try {
            $event = Admin::createEvent($vendor->id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        $images = $request->file('images');
        if ($event && $images) {
            foreach ($images as $key => $img) {
                try {
                    $path = FileService::storeFile($img, 'event_images');
                } catch (\Throwable $th) {
                    return Helper::apiRes($th->getMessage(), [], false, 401);
                }
                $image = new Image();
                $image->event_id = $event->id;
                $image->path = $path;
                $image->save();
            }
        }

        return Helper::apiRes('Event Created Successfully');
    }

    public function updateEvent(Request $request)
    {
        $event = Event::find($request->id);
        $vendor = session('current_vendor');

        try {
            $event = Admin::updateEvent($event, $request, $vendor);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        $images = $request->file('images');
        if ($event && $images) {
            foreach ($images as $key => $img) {
                try {
                    $path = FileService::storeFile($img, 'event_images');
                } catch (\Throwable $th) {
                    return Helper::apiRes($th->getMessage(), [], false, 401);
                }
                $image = new Image();
                $image->event_id = $event->id;
                $image->path = $path;
                $image->save();
            }
        }

        return Helper::apiRes('Event Updated Successfully', $event);
    }

    public function switchVendor()
    {
        session(['current_vendor' => null]);

        return redirect()->back();
    }

    public function switchVendorAccounts(Request $request)
    {
        $vendor = Vendor::findOrFail($request->vendor_id);

        $team = Team::findOrFail($request->vendor_id);
        session(['current_vendor' => $vendor]);
        if (!$request->user()->switchTeam($team)) {
            abort(403);
        }

        return redirect()->route('vendors.dashboard');
    }

    public function acceptInvitation(Request $request, TeamInvitation $invitation)
    {
        $user = auth()->user();

        $membership = new Membership();
        $membership->user_id = $user->id;
        $membership->team_id = $invitation->team_id;
        $membership->role = $invitation->role;
        $membership->save();

        $team = Team::findOrFail($invitation->team_id);

        $invitation->delete();

        session(['current_vendor' => $team->user->vendor]);
        if (!$request->user()->switchTeam($team)) {
            abort(403);
        }

        session()->flash('flash.banner', "Great! You have accepted the invitation to join the $team->name");
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('vendors.dashboard');
    }

    public function SortingMenu(Request $request)
    {
        $cmenus = $request->menus;
        foreach ($cmenus as  $key => $value) {
            Menu::find($value['id'])->update(['order' => $value['index']]);
        }

        return Helper::apiRes('Menu Sorted succesfully');
    }

    public function getVendorTransactions()
    {
        $user = auth()->user();
        $data['transactions'] = Transaction::where('vendor_id', $user->id)->get();

        return Inertia::render('Vendor/Transactions/index', $data);
    }

    public function SortingMenuItems(Request $request)
    {
        $cmenus = $request->menus;
        foreach ($cmenus as  $key => $value) {
            MenuCategoryItem::find($value['id'])->update(['order' => $value['index']]);
        }

        return Helper::apiRes('Item Sorted succesfully');
    }

    public function UpdateMenuItemStatus(Request $request)
    {
        $id = $request->id;
        $item = MenuCategoryItem::find($id);
        $status = $item->status === 'on' ? 'off' : 'on';
        MenuCategoryItem::find($id)->update(['status' => $status]);

        return Helper::apiRes('Item Updated succesfully');
    }

    public function guestsExport()
    {
        return Excel::download(new ExportsGuestExport, 'guests-collection.xlsx');

        return back();
    }

    public function menuSettings(Request $request)
    {

        // echo "Found";

        try {
            $vendor = session('current_vendor');
            $bg_type = $request->bg_type;
            $page = $request->page;
            $bg = $request->bg;
            $asImage = $page == 'dinesurf-menu' && $bg_type === 'image';
            $path = null;

            if ($page == 'pdf-menu') {
                $path = FileService::storeFile($request->file('pdf'), 'menu_setting_pdfs');
            }

            if ($asImage) {
                $path = FileService::storeFile($request->file('bg'), 'menu_setting_images');
            }

            $get_setting = MenuSetting::where('vendor_id', $vendor->id)->first();
            if ($get_setting) {
                $get_setting->bg_type = $bg_type;
                $get_setting->page = $page;
                $get_setting->pdf = $path ? $path : $get_setting->pdf;
                $get_setting->bg = $asImage ? $path : $bg;
                $get_setting->update();

                if ($get_setting) {
                    return Helper::apiRes('Setting updated succesfully');
                }

                return;
            }

            $setting = new MenuSetting;
            $setting->bg_type = $bg_type;
            $setting->bg = $asImage ? $path : $bg;
            $setting->page = $page;
            $setting->pdf = $path ? $path : $setting->pdf;
            $setting->vendor_id = $vendor->id;
            $setting->save();
            if ($setting) {
                return Helper::apiRes('Setting saved succesfully');
            }
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }
    }

    public function enableService(Request $request)
    {
        $service = $request->service;
        $vendor = session('current_vendor');
        $vendor = Vendor::find($vendor->id);

        if ($service == "mira") {
            $vendor = Mira::integrate($vendor);
            dispatch(new SetupMira(auth_id: $vendor->mira_id, vendor: $vendor));
        }

        session(['current_vendor' => $vendor]);

        return response()->json($vendor);
    }
}
