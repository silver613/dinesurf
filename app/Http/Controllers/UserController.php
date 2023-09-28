<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Jobs\ProcessBulkSms;
use App\Mail\SendMail;
use App\Models\Dinelist;
use App\Models\DinelistLike;
use App\Models\Event;
use App\Models\EventReservation;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPageViewCount;
use App\Services\AllServices\Admin;
use App\Services\AllServices\General;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function makeReservation(Request $request)
    {
        $vendor = Vendor::find($request->vendor_id);
        $user = User::find($request->user_id);
        $guest = null;

        if (Carbon::parse($request->date) < now()->startOfDay()) {
            return Helper::apiRes('Invalid Reservation Date, Reservation Date must either be today or in the future', [], false, 401);
        }

        if (General::isBlocked($vendor->blockLists, $user ? $user->email : $request->email)) {
            return Helper::apiRes("<p>Sorry you're not able to make a Reservations to $vendor->company_name</p> <p style='margin-top:15px'>Please contact the Restaurant <a style='color:blue;' href='mailto:$vendor->email'>$vendor->email</a> for more info</p>", [], false, 401);
        }

        if (! $user) {
            $user = User::where('email', $request->email)->first();
            if (! $user) {
                $guest = Guest::where('email', $request->email)->where('vendor_id', $vendor->id)->first();
                if (! $guest) {
                    $words = explode(' ', $request->name);

                    $guest = new Guest();
                    $guest->vendor_id = $vendor->id;
                    $guest->first_name = $words[0];
                    $guest->last_name = isset($words[1]) ? $words[1] : null;
                    $guest->email = $request->email;
                    $guest->phone = $request->phone;
                    $guest->save();
                }
            }
        }

        try {
            $reservation = Admin::createReservation($vendor->id, $request->party_size, $request->date, $request->time, $user ? $user->id : null, $guest ? $guest->id : null, false, $request->seating_preferences, $request->phone, $request->note, $request->source, null, $request->campaign_id);
        } catch (\Throwable $th) {
            throw $th;
        }

        if ($request->txnRef) {
            $txn = Transaction::where('reference', $request->txnRef)->first();
            if ($txn) {
                $txn->reservation_id = $reservation->id;
                $txn->save();
            }
        }

        return Helper::apiRes('Reservation Successfull', $reservation);
    }

    public function reservations()
    {
        $user = Auth::user();

        $upcoming_invitations = Invitation::whereHas('reservation', function ($q) {
            $q->where('date', '>=', now()->toDateString());
        })->select('reservation_id')->where('email', $user->email)->where('status', 'accepted')->orderBy('id', 'desc')->get();

        $past_invitations = Invitation::whereHas('reservation', function ($q) {
            $q->where('date', '<', now()->toDateString());
        })->select('reservation_id')->where('email', $user->email)->where('status', 'accepted')->orderBy('id', 'desc')->get();

        if ($user->phone_number) {
            $phone_upcoming_invitations = Invitation::whereHas('reservation', function ($q) {
                $q->where('date', '>=', now()->toDateString());
            })->select('reservation_id')->where('phone', $user->phone_number)->where('status', 'accepted')->orderBy('id', 'desc')->get();

            $phone_past_invitations = Invitation::whereHas('reservation', function ($q) {
                $q->where('date', '<', now()->toDateString());
            })->select('reservation_id')->where('phone', $user->phone_number)->where('status', 'accepted')->orderBy('id', 'desc')->get();

            $upcoming_invitations = $upcoming_invitations->merge($phone_upcoming_invitations);
            $past_invitations = $past_invitations->merge($phone_past_invitations);
        }

        $past_reservations_before = General::pastReservationsBefore()->with(['vendor', 'invitations'])->where('user_id', $user->id)->orWhereIn('id', $past_invitations)->take(5)->orderBy('date', 'desc')->get();
        $past_reservations_count_before = General::pastReservationsBefore()->where('user_id', $user->id)->orWhereIn('id', $past_invitations)->count();

        $past_reservations = General::pastReservations()->with(['vendor', 'invitations'])->where('user_id', $user->id)->take(5)->orderBy('date', 'desc')->get()->toArray();
        $past_reservations_count = General::pastReservations()->where('user_id', $user->id)->count();

        // dd($past_reservations);
        foreach ($past_reservations_before as $key => $value) {
            array_push($past_reservations, $value);
        }

        $past_reservations_count = $past_reservations_count + $past_reservations_count_before;

        $data = [
            'resource_name' => 'Reservations',
            'title' => 'Reservations',
            // 'reservations' => Reservation::with(['vendor','invitations'])->where('user_id', $user->id)->orderBy('id', 'desc')->get(),
            'upcoming_reservations' => General::upcomingReservations()->with(['vendor', 'invitations', 'transaction'])->where('user_id', $user->id)->orWhereIn('id', $upcoming_invitations)->take(5)->orderBy('date', 'asc')->orderBy('time', 'asc')->get(),
            'upcoming_reservations_count' => General::upcomingReservations()->where('user_id', $user->id)->orWhereIn('id', $upcoming_invitations)->count(),
            'past_reservations' => $past_reservations,
            'past_reservations_count' => $past_reservations_count,
        ];

        // dd($data);

        return Inertia::render('Reservations/Home', $data);
    }

    public function reservation($id)
    {
        $reservation = Reservation::with(['vendor', 'invitations', 'transaction'])->where('id', $id)->first();

        if (! $reservation) {
            abort(404, 'reservation not found');
        }

        $data = [
            'reservation' => $reservation,
            'vendor' => $reservation->vendor,
        ];

        return Inertia::render('Reservations/Index', $data);
    }

    public function editReservation($id)
    {
        $reservation = Reservation::find($id);

        if (! $reservation) {
            abort(404, 'reservation not found');
        }

        $vendor = $reservation->vendor;
        $vendor->load('seatingPreferences', 'days');

        $data = [
            'reservation' => $reservation,
            'vendor' => $vendor,
        ];

        return Inertia::render('Reservations/Edit', $data);
    }

    public function updateReservation(Request $request)
    {
        $reservation = Reservation::find($request->id);
        $reservation->party_size = $request->party_size;
        $reservation->date = $request->date;
        $reservation->phone = $request->phone;
        // $reservation->start_time = $request->start_time;
        // $reservation->end_time = $request->end_time;
        $reservation->time = $request->time;
        $reservation->note = $request->note;
        // $reservation->seating_preferences = $request->seating_preferences;
        $seating_preferences = implode(', ', (array) $request->seating_preferences);
        // $seating_preferences = substr($seating_preferences, 1);
        $reservation->seating_preferences = $seating_preferences;
        $reservation->save();

        $vendor = $reservation->vendor;

        if ($vendor) {
            $title = "A Reservation for $vendor->comapny_name has been modified";
            $msg = "<p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='https://app.dinesurf.com/admin/resources/reservations' class='btn reservation-btn'>View Reservation</a> </div>";

            Mail::to('martins@dinesurf.com')->queue(new SendMail('Martins', $title, $msg, action_log: [
                'route' => 'email',
                'type' => 'reservation_email',
                'content' => $msg,
                'action_by' => 'dinesurf',
            ]));
            Mail::to('joshua@dinesurf.com')->queue(new SendMail('Joshua', $title, $msg, action_log: [
                'route' => 'email',
                'type' => 'reservation_email',
                'content' => $msg,
                'action_by' => 'dinesurf',
            ]));
            Mail::to('itohan.ugberaese@gmail.com')->queue(new SendMail('Itohan', $title, $msg, action_log: [
                'route' => 'email',
                'type' => 'reservation_email',
                'content' => $msg,
                'action_by' => 'dinesurf',
            ]));
        }

        return Helper::apiRes('Reservation Updated', $reservation);
    }

    public function cancelReservation($id, $status)
    {
        $reservation = Reservation::find($id);

        if (! $reservation) {
            abort(404, 'reservation not found');
            throw new Exception('reservation not found', 404);
        }

        try {
            General::updateReservationStatus($reservation, $status);
        } catch (\Throwable $th) {
            throw $th;
        }

        return Helper::apiRes('Reservation Updated');
    }

    public function getReservation($id)
    {
        $reservation = Reservation::with(['vendor', 'invitations'])->where('id', $id)->first();

        if (! $reservation) {
            abort(404, 'reservation not found');
        }

        $data = [
            'reservation' => $reservation,
            'vendor' => $reservation->vendor,
            'user' => $reservation->user,
        ];

        return Helper::apiRes('Reservation', $data);
    }

    public function sendInvitation($invitation)
    {
        $note = $invitation->note ? "<p>Note: $invitation->note </p>" : '';
        $reservation = $invitation->reservation;
        $user = $reservation->user;
        $vendor = $reservation->vendor;

        if ($invitation->email) {
            Mail::to($invitation->email)->queue(new SendMail($invitation->name ?: 'Dear '.ucfirst($invitation->name), 'Reservation Invitation', "<p>$user->first_name $user->last_name has invited you to his/her Reservation with <a href='".route('restaurants.index', ['id' => $vendor->id])."'>$vendor->company_name.</a> </p> <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> $note  <p style='margin-top:20px;font-weight:900'>Do you Accept This Reservation Invitation?</p> <div class='reservation-div' style='display:flex'> <a href='".route('accept-invitation', ['id' => $invitation->id, 'reply' => 'yes'])."' class='btn reservation-btn' style='margin-right:10px'>Yes</a> <a href='".route('accept-invitation', ['id' => $reservation->id, 'reply' => 'no'])."' class='btn reservation-btn'>No</a> </div>", action_log: [
                'user_id' => $user->id,
                'route' => 'email',
                'type' => 'invitation_email',
                'vendor_id' => $vendor->id,
                'action_by' => 'vendor',
            ]));
        }

        if ($invitation->phone) {
            $greet = $invitation->name ?: 'Dear '.ucfirst($invitation->name);
            $short_text = $greet." \n$user->first_name $user->last_name has invited you to his/her Reservation with $vendor->company_name on Dinesurf.\n Scheduled for $reservation->formated_date at $reservation->formated_time \n $note \n Do you accept this invitation? \nif yes click ".route('accept-invitation', ['id' => $reservation->id, 'reply' => 'yes'])."\nif no click ".route('accept-invitation', ['id' => $reservation->id, 'reply' => 'no']);
            ProcessBulkSms::dispatchAfterResponse($invitation->phone, null, $short_text, 'Dinesurf', [
                'user_id' => $user->id,
                'route' => 'sms',
                'type' => 'invitation_text',
                'content' => $short_text,
                'vendor_id' => $vendor->id,
                'action_by' => 'vendor',
            ]);
        }
    }

    public function inviteGuest(Request $request)
    {
        $user = Auth::user();
        $reservation = Reservation::find($request->reservation_id);
        $user = $reservation->user;
        $vendor = $reservation->vendor;

        if (! $request->email && ! $request->phone) {
            return Helper::apiRes('You must input either an E-Mail or Phone Number', [], false, 422);
        }

        // CHeck for E-Mail
        $invitation = Invitation::where('reservation_id', $request->reservation_id)->whereNotNull('email')->where('email', $request->email)->first();

        if ($invitation) {
            return Helper::apiRes('Email Already Invited', [], false, 422);
        }

        // CHeck for Phone
        $invitation = Invitation::where('reservation_id', $request->reservation_id)->whereNotNull('phone')->where('phone', $request->phone)->first();

        if ($invitation) {
            return Helper::apiRes('Phone Already Invited', [], false, 422);
        }

        // check for inviting yourself
        if ($request->email == $user->email || $request->phone == $user->phone_number) {
            return Helper::apiRes('Phone or E-Mail is the same as you, You cannot Invite yourself', [], false, 422);
        }

        $invitation = new Invitation();
        $invitation->reservation_id = $request->reservation_id;
        $invitation->email = $request->email ? $request->email : null;
        $invitation->phone = $request->phone ? $request->phone : null;
        $invitation->note = $request->note;
        $invitation->name = $request->name;
        $invitation->save();

        $this->sendInvitation($invitation);

        return Helper::apiRes('Invitation Created');
    }

    public function deleteGuest($id)
    {
        $invitation = Invitation::find($id);

        if (! $invitation) {
            abort(404, 'invitation not found');
        }

        $invitation->delete();

        return Helper::apiRes('Invitation Deleted');
    }

    public function resendInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $this->sendInvitation($invitation);

        return Helper::apiRes('Invitation Resent');
    }

    public function updateInvitation(Request $request)
    {
        $invitation = Invitation::find($request->invitation_id);

        if (! $invitation) {
            abort(404, 'invitation not found');
        }

        if ($request->status == 'accepted' || $request->status == 'declined') {
            $invitation->status = $request->status;
        }

        if ($request->status == 'remove') {
            $invitation->removed = true;
        }

        $invitation->save();

        return Helper::apiRes('Invitation Updated');
    }

    public function acceptInvitation(Request $request)
    {
        $invitation = Invitation::findOrFail($request->id);
        $status = null;

        if ($request->reply == 'yes') {
            $invitation->status = 'accepted';
            session()->flash('flash.banner', 'Invitation Accepted!');
            session()->flash('flash.bannerStyle', 'success');
            $invitation->save();
        }

        if ($request->reply == 'no') {
            $invitation->status = 'declined';
            session()->flash('flash.banner', 'Invitation Declined!');
            session()->flash('flash.bannerStyle', 'success');
            $invitation->save();
        }

        if ($invitation->status == 'accepted') {
            $status = 'Invitation Accepted';
        }

        if ($invitation->status == 'declined') {
            $status = 'Invitation Declined';
        }

        $reservation = $invitation->reservation;
        $vendor = $reservation->vendor;
        $user = $reservation->user;

        if ($user->email) {
            Mail::to($user->email)->queue(new SendMail($user->first_name, 'Reservation Invitation Update', "<p>$invitation->email $invitation->phone has $invitation->status your invitation to your reservation with $vendor->company_name.</p><div class='reservation-div'> <a href='".route('reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", action_log: [
                'user_id' => $user->id,
                'route' => 'email',
                'type' => 'invitation_email',
                'vendor_id' => $vendor->id,
                'action_by' => 'vendor',
            ]));
        }

        if ($user->phone_number) {
            $short_text = "\n$invitation->email $invitation->phone has $invitation->status your invitation to your reservation with $vendor->company_name \n click here ".route('reservation', ['id' => $reservation->id]).' to view reservation';
            ProcessBulkSms::dispatchAfterResponse($reservation->phone, $user->first_name, $short_text, 'Dinesurf', [
                'user_id' => $user->id,
                'route' => 'sms',
                'type' => 'invitation_status_text',
                'content' => $short_text,
                'vendor_id' => $vendor->id,
                'action_by' => 'vendor',
            ]);
        }

        $invitation->load('reservation', 'reservation.user', 'reservation.vendor');

        return Inertia::render('Reservations/AcceptInvitation', ['invitation' => $invitation, 'status' => $status]);
    }

    public function allReservations($name = null)
    {
        $user = Auth::user();

        // $invitations = Invitation::select('reservation_id')->where('email', $user->email)->where("removed", false)->get();
        $upcoming_invitations = Invitation::whereHas('reservation', function ($q) {
            $q->where('date', '>=', now()->toDateString());
        })->select('reservation_id')->where('email', $user->email)->where('status', 'accepted')->orderBy('id', 'desc')->get();

        $past_invitations = Invitation::whereHas('reservation', function ($q) {
            $q->where('date', '<', now()->toDateString());
        })->select('reservation_id')->where('email', $user->email)->where('status', 'accepted')->orderBy('id', 'desc')->get();

        if ($user->phone_number) {
            $phone_upcoming_invitations = Invitation::whereHas('reservation', function ($q) {
                $q->where('date', '>=', now()->toDateString());
            })->select('reservation_id')->where('phone', $user->phone_number)->where('status', 'accepted')->orderBy('id', 'desc')->get();

            $phone_past_invitations = Invitation::whereHas('reservation', function ($q) {
                $q->where('date', '<', now()->toDateString());
            })->select('reservation_id')->where('phone', $user->phone_number)->where('status', 'accepted')->orderBy('id', 'desc')->get();

            $upcoming_invitations = $upcoming_invitations->merge($phone_upcoming_invitations);
            $past_invitations = $past_invitations->merge($phone_past_invitations);
        }

        $query = Reservation::with(['vendor', 'invitations'])->where('user_id', $user->id)->orderBy('date', 'desc');
        $size = $query->count();
        $reservations = $query->paginate(15);

        if ($name == 'upcoming') {
            $query = General::upcomingReservations()->with(['vendor', 'invitations'])->where('user_id', $user->id)->orWhereIn('id', $upcoming_invitations)->orderBy('date', 'desc');
            $size = General::upcomingReservations()->where('user_id', $user->id)->orWhereIn('id', $upcoming_invitations)->count();
            $reservations = $query->paginate(15);
        }

        if ($name == 'past') {
            $query = General::pastReservationsBefore()->with(['vendor', 'invitations'])->where('user_id', $user->id)->orWhereIn('id', $past_invitations)->orderBy('date', 'desc');
            $size = General::pastReservationsBefore()->where('user_id', $user->id)->orWhereIn('id', $past_invitations)->count();
            $reservations = $query->paginate(15);
        }

        if (! $name) {
            $name = 'All';
        }

        $data = ['resource_name' => $name, 'reservations' => $reservations, 'size' => $size];

        return Inertia::render('Reservations/All', $data);
    }

    public function createReview(Request $request)
    {
        $user = Auth::user();

        if ($request->type == 'vendor') {
            $check = Reservation::approved()->where('vendor_id', $request->vendor_id)->where('user_id', $user->id)->first();
            if (! $check) {
                return Helper::apiRes("You cannot drop a review on a restaurant you haven't yet made an approved reservation with or if the reservation is still upcoming", [], false, 401);
            }
            $review = Review::where('vendor_id', $request->vendor_id)->where('user_id', $user->id)->first();
            if (! $review) {
                $review = new Review;
            }
            $review->vendor_id = $request->vendor_id;
        }

        $review->type = $request->type;
        $review->content = $request->content;
        $review->user_id = $user->id;
        $review->name = $user->first_name.' '.$user->last_name;
        $review->avatar = $user->profile_photo_url;
        $review->stars = $request->rating;
        $review->save();

        return Helper::apiRes('Review Created or Updated', $review);
    }

    public function deleteReview($id)
    {
        $review = Review::find($id);
        $user = Auth::user();

        if (! $review) {
            return Helper::apiRes('Review Not Found', [], false, 404);
        }

        if ($review->user->id != $user->id) {
            return Helper::apiRes('Unauthorized user', [], false, 401);
        }

        $review->forceDelete();

        return Helper::apiRes('Review Deleted');
    }

    public function createEventReservation(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $event = Event::find($request->event_id);
        $vendor = Vendor::find($request->vendor_id);

        if (! $user) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->name;
            $user->save();
        }

        $eventReservation = new  EventReservation;
        $eventReservation->event_date = $request->event_date;
        $eventReservation->event_time = $request->event_time;
        $eventReservation->reserve_seat = $request->reserve_seat;
        $eventReservation->vendor_id = $request->vendor_id;
        $eventReservation->event_id = $request->event_id;
        $eventReservation->user_id = $user->id;
        $eventReservation->total_price = $request->total_price;
        $eventReservation->payment_ref = $request->payment_ref;
        $eventReservation->isPaid = $request->isPaid;
        $eventReservation->payment_status = $request->payment_status;
        $eventReservation->transaction_ref = $request->transaction_ref;
        $eventReservation->package = $request->package;
        $eventReservation->save();
        if ($eventReservation) {
            $name = $user->name ? $user->name : $user->first_name;

            $description = "
         <p>We hope this message find you well,This is to let you know that your   reservation  for  <strong style='text-transform: capitalize;'> $event->name </strong> at  <strong> $vendor->company_name </strong> has been successful, to see the event details ?  click the button below 👇,  and don't forget to add it to your calendar, See you.😃</p>
       
         <div class='reservation-div'> 
         <a href='".route('getEvent', ['id' => $event->id])."' class='btn reservation-btn'>View the event</a> 
         
         </div>";

            if ($user->email) {
                Mail::to($user->email)->queue(new SendMail($name, 'Dinesurf Event Reservation', $description, action_log: [
                    'user_id' => $user->id,
                    'route' => 'email',
                    'type' => 'invitation_email',
                    'vendor_id' => $vendor->id,
                    'action_by' => 'vendor',
                ]));

                return Helper::apiRes('Event Reservation Created Succesfully', $user);
            }
        }
    }

    public function eventReservations()
    {
        $user = Auth::user();

        $data['reservations'] = EventReservation::with(['event', 'vendor'])->where('user_id', $user->id)->orderby('event_date', 'desc')->get();

        return Inertia::render('Reservations/Event', $data);
    }

    public function CreateDinelist(Request $request)
    {
        $user = Auth::user();
        try {
            $dinelist = Dinelist::where('name', $request->name)->first();
            if ($dinelist) {
                return Helper::apiRes('This name already exist, please try another name!', [], false, 400);
            }
            $list = new  Dinelist;
            $list->name = $request->name;
            $list->isPrivate = $request->isPrivate === 'true' ? 1 : 0;
            $list->user_id = $user->id;
            $list->save();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('List Created Successfully', $list);
    }

    public function DeletDinelist($id)
    {
        try {
            $list = Dinelist::find($id);
            $list->delete();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('List Deleted Successfully', $list);
    }

    public function EditDinelist(Request $request)
    {
        $id = $request->id;

        try {
            $dinelist = Dinelist::where('name', $request->name)->first();
            if ($dinelist && $dinelist->id != $id) {
                return Helper::apiRes('This name already exist, please try another name!', [], false, 400);
            }
            $list = Dinelist::find($id);
            $list->name = $request->name;
            $list->isPrivate = $request->isPrivate === 'true' ? 1 : 0;
            $list->update();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 401);
        }

        return Helper::apiRes('List Updated Successfully', $list);
    }

    public function ViewDinelist(Request $request, $slug)
    {

        // featured restauranrts as recommended restaurants
        $vendors = Vendor::approved()->visible()
        ->where('type', 'restaurant')->where('featured', true)->limit(10)->get()->toArray();

        $name = Str::title(str_replace('-', ' ', $slug));
        $list = Dinelist::with(['vendors', 'likes'])->where('name', $name)->first();

        if (! $list) {
            abort(404, 'Dinelist not found');
            throw new Exception('Dinelist not found', 404);
        }

        $userIp = $request->ip();
        $this->dinelistVendorPageCount($userIp, $list->id);

        $list_vendors = $list->vendors->toArray();
        $filteredVendor = [];
        $list_vendors_ids = array_column($list_vendors, 'id');
        foreach ($vendors as $vendor) {
            if (! in_array($vendor['id'], $list_vendors_ids)) {
                $filteredVendor[] = $vendor;
            }
        }

        $loggedInUser = Auth::user();
        $isLogged = Auth::user() ? true : false;
        $isSameUser = $isLogged && $loggedInUser->id == $list?->user_id ? true : false;
        $dinelist_user = User::find($list->user_id);

        $data = [
            'resource_name' => 'ViewDineList '.$list?->name,
            'vendors' => $filteredVendor,
            'user' => $loggedInUser,
            'dinelist_user' => $dinelist_user,
            'isLogged' => $isLogged,
            'list' => $list,
            'isSameUser' => $isSameUser,
        ];
        $date = Carbon::parse($list->updated_at)->format('d M, Y');
;
        $dinnerName = $dinelist_user->displayname ? $dinelist_user->displayname : $dinelist_user->name;
        $pageMetaTitle = $list->name.' by '.$dinnerName.' (Updated '.$date.') - Dinesurf';
        $pageMetaDescription = $list->name." - Explore restaurant recommendation made by ".$dinnerName.". See Restaurant Rankings, Community tips and Prices on Dinesurf";
        $pageMetaKeywords = $list->name.', '.$dinelist_user->name.", dinesurf, restaurants, Lagos restaurants list, abuja restaurants list,  reviews of restaurants, food, dining";
        $pageMetaUrl =route('view.dinelist', ['slug' => $list->slug]);
      
        $data['title'] =$pageMetaTitle;
        $data['description'] =$pageMetaDescription;
        $data['keywords'] =$pageMetaKeywords;
        $data['metaUrl'] =$pageMetaUrl;
        $data['metaImage'] =count($vendors) > 0 ? $list?->vendors[0]?->banner_url : null;


        return Inertia::render('Vendor/Frontend/ViewDinelist', $data);
    }

    public function AddVendorToDinelist(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $dinelist_id = $request->dinelist_id;
        $isVendor = $request->isVendor;

        if ($isVendor) {
            $dinelist = Dinelist::find($dinelist_id);
            $dinelist->vendors()->detach($vendor_id);
        } else {
            $vendor = Vendor::find($vendor_id);
            $dinelist = Dinelist::find($dinelist_id);
            $dinelist->vendors()->attach($vendor);
        }

        return redirect()->back();
    }

    public function LikeDinelist(Request $request)
    {
        $dinelist_id = $request->query('dinelist_id');
        if (! $dinelist_id) {
            return;
        }

        $user = Auth::user();
        $like = DinelistLike::where('dinelist_id', $dinelist_id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $like = new DinelistLike();
            $like->dinelist_id = $dinelist_id;
            $like->user_id = $user->id;
            $like->save();
        }

        return Helper::apiRes('Toggled the Like');
    }

    public function SingleDinelist($id)
    {
        $list = Dinelist::with(['likes'])->where('id', $id)->first();
        if (! $list) {
            abort(404, 'Dinelist not found');
            throw new Exception('Dinelist not found', 404);
        }
        $data = $list;

        return Helper::apiRes('dinelist retrieved', $data);
    }

    public function ExploreDinelist()
    {
        return Inertia::render('Vendor/Frontend/ExploreDinelist');
    }

    public function GetALlDinelist(Request $request)
    {
        $search = $request->search;
        $tab = $request->tab;

        // list
        $dinelists = Dinelist::with(['user', 'vendors', 'likes'])
        ->when($tab === 'list', function ($query) use ($search) {
            return $query->where('name', 'LIKE', '%'.$search.'%');
        })->where('isPrivate', false)->get();

        // users
        $users = User::with('dinelists.likes')
         ->when($tab === 'users', function ($query) use ($search) {
             return $query->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('first_name', 'LIKE', '%'.$search.'%')
            ->orWhere('last_name', 'LIKE', '%'.$search.'%')
            ->orWhere('username', 'LIKE', '%'.$search.'%')
            ->orWhere('email', 'LIKE', '%'.$search.'%');
         })
        ->get()->toArray();

        $newUsers = [];
        foreach ($users as $user) {
            if (count($user['dinelists']) > 0) {
                $newUsers[] = $user;
            }
        }
        // suggested to get dinelist with atleast one like
        $get_suggested = Dinelist::with(['user', 'vendors', 'likes'])
        ->when($tab === 'suggested', function ($query) use ($search) {
            return $query->where('name', 'LIKE', '%'.$search.'%');
        })->where('isPrivate', false)->get()->toArray();

        $suggesteds = [];
        foreach ($get_suggested as $sg) {
            if (count($sg['likes']) > 0) {
                $suggesteds[] = $sg;
            }
        }

        $data['dinelists'] = $dinelists;
        $data['users'] = $newUsers;
        $data['suggesteds'] = $suggesteds;
        $data['search'] = $search;

        return Helper::apiRes('dinelist retreived', $data);
    }

    public function AddToDinelist(Request $request)
    {
        $id = $request->id;
        $findDinelist = Dinelist::with(['vendors', 'likes'])->where('id', $id)->first();

        if (! $findDinelist) {
            abort(404, 'Dinelist not found');
            throw new Exception('Dinelist not found', 404);
        }
        $loggedInUser = Auth::user();
        $isLogged = Auth::user() ? true : false;
        $isSameUser = $isLogged && $loggedInUser->id == $findDinelist?->user_id ? true : false;

        $data['dinelist'] = $findDinelist;
        $data['isSameUser'] = $isSameUser;

        return Inertia::render('Vendor/Frontend/AddToDinelist', $data);
    }

    public function dinelistRestaurants(Request $request)
    {
        $dinelist_id = $request->dinelist_id;
        $search = $request->search;
        $take = $request->take;

        $path = route('add.to.dinelist', ['id' => $dinelist_id]);

        $query = Vendor::approved()->visible();
        $query = $query->where('type', 'restaurant')->with(['cuisines', 'days',  'likes'])
                 ->when($take, function ($query) use ($take) {
                     return $query->take($take);
                 })
                ->when($search, function ($query) use ($search) {
                    return $query->where('company_name', 'LIKE', '%'.$search.'%');
                });

        if ($take) {
            $models = $query->get()->each->setAppends(['average_menu_price_nairas',  'hours', 'banner_url', 'average_rating',  'liked']);
        } else {
            $models = $query->paginate()->withQueryString();
            if ($path) {
                $models->withPath($path);
            }
        }

        return response()->json([
            'models' => $models,
            'search' => $search,
        ]);
    }

    public function LikedBy($slug)
    {
        $name = Str::title(str_replace('-', ' ', $slug));
        $list = Dinelist::where('name', $name)->first();

        if (! $list) {
            abort(404, 'Dinelist not found');
            throw new Exception('Dinelist not found', 404);
        }
        $data['getLikes'] = DinelistLike::with('user')->where('dinelist_id', $list->id)->get();
        $data['pageViewCount'] = VendorPageViewCount::where('dinelist_id', $list->id)->get();
        $data['slug'] = $slug;
        $data['list'] = $list;

        return Inertia::render('Vendor/Frontend/Likedby', $data);
    }

    public function likedbyApi(Request $request)
    {
        $search = $request->search;
        $dinelist_id = $request->dinelist_id;
        $query = DinelistLike::with('user')->where('dinelist_id', $dinelist_id)
         ->when($search, function ($query) use ($search) {
             return $query->whereHas('user', function ($query) use ($search) {
                 $query->where('first_name', 'LIKE', '%'.$search.'%')
                ->orWhere('last_name', 'LIKE', '%'.$search.'%')
                ->orWhere('username', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%');
             });
         })->orderBy('created_at', 'Desc');

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models,
            'search' => $search,
        ]);
    }

    public function dinelistVendorPageCount($userIp, $dinelist_id)
    {
        $user = Auth::user();
        $user_id = $user ? $user->id : null;

        //  check if ip exists
        $checkUser = VendorPageViewCount::where('dinelist_id', $dinelist_id)->where('ip', $userIp)->first();

        if ($checkUser) {
            $checkUser->count = $checkUser->count + 1;
            $checkUser->ip = $checkUser->ip;
            $checkUser->dinelist_id = $checkUser->dinelist_id;
            $checkUser->location = null;
            $checkUser->save();

            return;
        }

        $viewCount = new VendorPageViewCount;
        $viewCount->ip = $userIp;
        $viewCount->user_id = $user_id;
        $viewCount->dinelist_id = $dinelist_id;
        $viewCount->location = null;
        $viewCount->count = 1;
        $viewCount->save();
    }
}
