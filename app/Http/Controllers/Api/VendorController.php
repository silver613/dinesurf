<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use App\Models\ActionLog;
use App\Models\Guest;
use App\Models\Membership;
use App\Models\Reservation;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\Tutorial;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPageViewCount;
use App\Services\AllServices\General;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::whereApproved(true)->get();

        return Helper::apiRes('vendors', VendorResource::collection($vendors));
    }

    public function show(Vendor $vendor)
    {
        if (! $vendor->approved) {
            return Helper::apiRes(['message' => 'Vendor is not approved.'], 403);
        }

        return Helper::apiRes('vendor', new VendorResource($vendor));
    }

    public function reservations(Request $request)
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'status', 'reservation_start_date', 'reservation_end_date', 'type', 'view', 'date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getReservations($vendor, $filters);

        $query->with(['invitations', 'user', 'guest']);

        $vendor->load('seatingPreferences', 'days');

        if ($filters->view == 'calendar') {
            $dates_with_reservations = DB::table('reservations')
                ->where('vendor_id', $vendor->id)
                ->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->select(DB::raw('count(*) as count,date'))
                ->groupBy('date')
                ->pluck('date');
        }

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('reservations'),
            'dates_with_reservations' => isset($dates_with_reservations) ? $dates_with_reservations : [],
            'vendor' => $vendor,
        ]);
    }

    public function guests(Request $request)
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'approved', 'birthday', 'birthday_date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getGuests($vendor, $filters);

        $vendor->load('blockLists');

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('guests'),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function menus(Request $request)
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getMenus($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('menus'),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function menuCategories(Request $request)
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'menu_id'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getMenuCategories($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath($filters->menu_id),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function menuCategoryItems(Request $request)
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'category_id', 'menu_id'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getMenuCategoryItems($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath($filters->menu_id),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function Tutorial(Request $request)
    {
        $tutorial = Tutorial::all();

        return response()->json([
            'tutorial' => $tutorial,
        ]);
    }

    public function metrics(Request $request)
    {
        $type = $request->type;
        $data = [];
        $vendor = session('current_vendor');
        $start = $request->start_date ? Carbon::parse($request->start_date)->toDateTimeString() : null;
        $end = $request->end_date ? Carbon::parse($request->end_date)->toDateTimeString() : null;
        $end_day_start = Carbon::parse($end)->startOfDay()->toDateTimeString() ?? now()->startOfDay()->toDateTimeString();
        $end_day_end = Carbon::parse($end)->endOfDay()->toDateTimeString() ?? now()->endOfDay()->toDateTimeString();

        if ($type == 'reservations-per-day') {
            $value = Reservation::where('vendor_id', $vendor->id)->where('created_at', '>=', now()->startOfDay())->count();

            $reservations = Reservation::where('vendor_id', $vendor->id)->select(DB::raw('DATE(`created_at`) as date_result, COUNT(*) as aggregate'))
                ->where('created_at', '>=', now()->subDays(30)->toDateTimeString())
                ->groupBy('date_result')
                ->orderBy('date_result')
                ->get();

            $period = CarbonPeriod::create(now()->subDays(30)->toDateString(), now()->toDateString());

            foreach ($period as $date) {
                $series[$date->format('M j')] = 0;
                foreach ($reservations as $day) {
                    $formated_date = $date->toDateString();
                    if ($formated_date == $day->date_result) {
                        $series[$date->format('M j')] = $day->aggregate;
                    }
                }
            }
            $data = ['value' => $value, 'series' => $series];
        }

        if ($type == 'total-reservations') {
            $value = Reservation::where('vendor_id', $vendor->id)
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->count();
            $data['value'] = $value;
            $count_after = Reservation::where('vendor_id', $vendor->id)->where('created_at', '>=', $end_day_start)->where('created_at', '<=', $end_day_end)->count();
            $data['increaseOrDecreaseLabel'] = $count_after > 0 ? 'increase' : null;
            $count_after = $count_after <= 0 ? 1 : $count_after;
            $value = $value <= 0 ? 1 : $value;
            $data['growthPercentage'] = ($count_after / $value) * 100;
        }

        if ($type == 'reservations-per-status') {
            $total = Reservation::where('vendor_id', $vendor->id)
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->count();

            $reservations = Reservation::where('vendor_id', $vendor->id)
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->select(DB::raw('status as status, COUNT(*) as aggregate'))
                ->groupBy('status')
                ->orderBy('aggregate', 'DESC')
                ->get();

            $value = [];

            foreach ($reservations as $statusObject) {
                $color = null;
                if ($statusObject->status == 'approved') {
                    $color = '#38a169';
                }
                if ($statusObject->status == 'pending') {
                    $color = '#f2cb22';
                }
                if ($statusObject->status == 'cancelled') {
                    $color = '#e53e3e';
                }

                array_push($value, [
                    'color' => $color,
                    'label' => ucfirst($statusObject->status),
                    'value' => $statusObject->aggregate,
                    'percentage' => ($statusObject->aggregate / $total) * 100,
                ]);
            }

            $data = ['value' => $value];
        }

        if ($type == 'upcoming-reservations') {
            $value = General::upcomingReservations()->where('vendor_id', $vendor->id)->count();

            $value_yesterday = General::upcomingReservations()->where('vendor_id', $vendor->id)->where('created_at', '<', now()->startOfDay()->toDateTimeString())->count();
            $value_today = General::upcomingReservations()->where('vendor_id', $vendor->id)->where('created_at', '>', now()->startOfDay()->toDateTimeString())->count();

            $data = ['value' => $value];
            $data['increaseOrDecreaseLabel'] = $value >= $value_yesterday ? 'increase' : 'decrease';
            $data['growthPercentage'] = ($value_today / ($value_yesterday ?: 1)) * 100;
        }

        if ($type == 'past-reservations') {
            $past_reservations_count_before = General::pastReservationsBefore()->where('vendor_id', $vendor->id)->count();

            $past_reservations_count = General::pastReservations()->where('vendor_id', $vendor->id)->count();

            $value = $past_reservations_count + $past_reservations_count_before;

            $data = ['value' => $value];
        }

        if ($type == 'total-revenue') {
            $seated_guests = General::pastReservationsBefore()->where('vendor_id', $vendor->id)->where('status', 'approved')->sum('party_size');
            $value = $seated_guests * $vendor->average_menu_price;

            $data = ['value' => $value];
        }

        if ($type == 'average-reservations') {
            $days = $request->days ?: 30;

            $value_yesterday = Reservation::where('vendor_id', $vendor->id)
                ->where('created_at', '>=', now()->subDays($days)->toDateTimeString())
                ->where('created_at', '<', now()->startOfDay()->toDateTimeString())
                ->count();

            $average_yesterday = $value_yesterday / $days;

            $value_now = Reservation::where('vendor_id', $vendor->id)
                ->where('created_at', '>=', now()->subDays($days)->toDateTimeString())
                ->count();

            $average_now = $value_now / $days;

            $data['increaseOrDecreaseLabel'] = $average_now >= $average_yesterday ? 'increase' : 'decrease';
            $data['growthPercentage'] = ($average_now / ($average_yesterday ?: 1)) * 100;
            $data = ['value' => $average_now];
        }

        if ($type == 'saved-guests') {
            $value = Guest::where('vendor_id', $vendor->id)->count();
            $value_yesterday = Guest::where('vendor_id', $vendor->id)->where('created_at', '<', now()->startOfDay()->toDateTimeString())->count();
            $value_today = Guest::where('vendor_id', $vendor->id)->where('created_at', '>', now()->startOfDay()->toDateTimeString())->count();
            $data['increaseOrDecreaseLabel'] = $value >= $value_yesterday ? 'increase' : 'decrease';
            $data['growthPercentage'] = ($value_today / ($value_yesterday ?: 1)) * 100;
            $data = ['value' => $value];
        }

        if ($type == 'count-guests') {
            $value = Reservation::where('vendor_id', $vendor->id)->sum('party_size');
            $value_yesterday = Reservation::where('vendor_id', $vendor->id)->where('created_at', '<', now()->startOfDay()->toDateTimeString())->sum('party_size');
            $value_today = Reservation::where('vendor_id', $vendor->id)->where('created_at', '>', now()->startOfDay()->toDateTimeString())->sum('party_size');
            $data['increaseOrDecreaseLabel'] = $value >= $value_yesterday ? 'increase' : 'decrease';
            $data['growthPercentage'] = ($value_today / ($value_yesterday ?: 1)) * 100;
            $data = ['value' => $value];
        }

        if ($type == 'number-of-sms') {
            $value = ActionLog::where('vendor_id', $vendor->id)->where('type', 'sms')->count();
            $data = ['value' => $value];
        }

        if ($type == 'number-of-emails') {
            $value = ActionLog::where('vendor_id', $vendor->id)->where('type', 'email')->count();
            $data = ['value' => $value];
        }

        if ($type == 'hours-saved') {
            $sms_count = ActionLog::where('vendor_id', $vendor->id)->where('route', 'sms')->count();
            $email_count = ActionLog::where('vendor_id', $vendor->id)->where('route', 'email')->count();
            $whatsapp_count = ActionLog::where('vendor_id', $vendor->id)->where('route', 'whatsapp')->count();

            $sms_mins = $sms_count * 3;
            $email_mins = $email_count * 3;
            $whatsapp_mins = $whatsapp_count * 3;

            $total_mins = $sms_mins + $email_mins + $whatsapp_mins;
            $hours_saved = $total_mins > 0 ? $total_mins / 60 : 0;

            $data = ['value' => $hours_saved];
        }

        if ($type == 'reservations-per-source') {
            $total = Reservation::where('vendor_id', $vendor->id)
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->count();

            $reservations = Reservation::where('vendor_id', $vendor->id)
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->select(DB::raw('source as source, COUNT(*) as aggregate'))
                ->groupBy('source')
                ->orderBy('aggregate', 'DESC')
                ->get();

            $value = [];

            foreach ($reservations as $sourceObject) {
                $color = null;
                if (! $sourceObject->source || $sourceObject->source == 'dinesurf') {
                    $color = '#8cc63f';
                }
                if ($sourceObject->source == 'Facebook') {
                    $color = '#4267B2';
                }
                if ($sourceObject->source == 'Instagram') {
                    $color = '#C13584';
                }
                if ($sourceObject->source == 'Whatsapp') {
                    $color = '#363636';
                }
                if ($sourceObject->source == 'Walk-In') {
                    $color = '#d82b2b';
                }

                array_push($value, [
                    'color' => $color,
                    'label' => $sourceObject->source ? ucfirst($sourceObject->source) : 'Dinesurf Default',
                    'value' => $sourceObject->aggregate,
                    'percentage' => ($sourceObject->aggregate / $total) * 100,
                ]);
            }

            $data = ['value' => $value];
        }

        if ($type == 'marketing-actions-count') {
            $total = ActionLog::where('vendor_id', $vendor->id)
                ->whereIn('route', ['sms', 'email', 'whatsapp'])
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->count();

            $actions = ActionLog::where('vendor_id', $vendor->id)
                ->whereIn('route', ['sms', 'email', 'whatsapp'])
                ->when($start, function ($query) use ($start) {
                    return $query->where('created_at', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    return $query->where('created_at', '<=', $end);
                })
                ->select(DB::raw('route as route, COUNT(*) as aggregate'))
                ->groupBy('route')
                ->orderBy('aggregate', 'DESC')
                ->get();

            $value = [];

            foreach ($actions as $routeObject) {
                $color = null;
                if ($routeObject->route == 'whatsapp') {
                    $color = '#8cc63f';
                }
                if ($routeObject->route == 'email') {
                    $color = '#d82b2b';
                }
                if ($routeObject->route == 'sms') {
                    $color = '#363636';
                }

                array_push($value, [
                    'color' => $color,
                    'label' => ucfirst($routeObject->route),
                    'value' => $routeObject->aggregate,
                    'percentage' => ($routeObject->aggregate / $total) * 100,
                ]);
            }

            $data = ['value' => $value];
        }

        if ($type == 'total-page-view') {
            $totalPageCount = VendorPageViewCount::where('vendor_id', $vendor->id)->count();
            $totalVisitCount = VendorPageViewCount::where('vendor_id', $vendor->id)->sum('count');
            // 'visitValue' => $totalVisitCount
            $data = ['value' => $totalVisitCount];
        }

        return response()->json($data);
    }

    public function countGuestReservations(Request $request)
    {
        $count = Reservation::where('guest_id', $request->guest_id)->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    public function events()
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'sort'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::events($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('events'),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function createVendor(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $vendor = new Vendor();
        $vendor->user_id = $user->id;
        $vendor->company_name = $request->company_name;
        $vendor->company_address = $request->company_address;
        $vendor->phone_number = $user->phone_number;
        $vendor->email = $user->email;
        $vendor->company_email = $user->email;
        $vendor->first_name = $user->first_name;
        $vendor->last_name = $user->last_name;
        $vendor->type = 'restaurant';
        $vendor->save();

        return Helper::apiRes('Vendor Created', $vendor);
    }

    public function vendorAccounts(Request $request)
    {
        $user = auth()->user();
        $vendors = [];

        $user_vendors = Vendor::where('user_id', $user->id)->get();
        foreach ($user_vendors as $key => $vendor) {
            array_push($vendors, $vendor);
        }

        foreach ($user->teams as $key => $team) {
            $vendor = Vendor::where('user_id', $team->user_id)->first();
            if ($vendor) {
                array_push($vendors, $vendor);
            }
        }

        return Helper::apiRes('vendors', $vendors);
    }

    public function vendorInvitations()
    {
        $email = auth()->user()->email;
        $invitations = TeamInvitation::where('email', $email)->with(['team'])->get();

        return Helper::apiRes('invitations', $invitations);
    }

    public function acceptInvitation(Request $request)
    {
        $user = auth()->user();
        $invitation = TeamInvitation::findOrFail($request->invitation_id);

        $membership = new Membership();
        $membership->user_id = $user->id;
        $membership->team_id = $invitation->team_id;
        $membership->role = $invitation->role;
        $membership->save();

        $team = Team::findOrFail($invitation->team_id);

        $invitation->delete();

        session(['current_vendor' => $team->user->vendor]);

        session()->flash('flash.banner', "Great! You have accepted the invitation to join the $team->name");
        session()->flash('flash.bannerStyle', 'success');

        return Helper::apiRes('team_id', $invitation->team_id);
    }

    public function leaveCurrentVendor()
    {
        session(['current_vendor' => null]);

        return Helper::apiRes('done');
    }

    public function getVendorTransactions()
    {
        $vendor = session('current_vendor');

        $query_params = ['search', 'sort'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::transactions($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('transactions'),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function order(){
        $vendor = session('current_vendor');

        $query_params = ['search', 'tab'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::orders($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('order-management'),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    // Menu Apis
}
