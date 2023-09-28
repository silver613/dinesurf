<?php

namespace App\Services\AllServices;

use App\Jobs\ProcessBulkSms;
use App\Jobs\ProcessSms;
use App\Mail\SendMail;
use App\Models\BlockList;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\SearchLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLog;
use App\Models\Vendor;
use App\Services\Google\GoogleReviews;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Order;
class General
{
    public static function logUserForTheDay(User $user)
    {
        if (isset($user->approved)) {
            if (! $user->approved) {
                throw new Exception('Account Blocked', 401);
            }
        }

        // Log User for the day
        if (isset($user->userLogs)) {
            $dateTime = now()->startOfDay()->toDateTimeString();
            $log = $user->userLogs->where('created_at', '>=', $dateTime)->first();
            if (! $log) {
                if ($user->created_at < $dateTime && ! $user->is_admin) {
                    $newLog = new UserLog();
                    $newLog->user_id = $user->id;
                    $newLog->save();
                }
            }
        }
    }

    public static function logSearch(User $user, $query, $model_id = null, $party_size = null, $type = 'restaurant')
    {
        $log = SearchLog::where('query', $query)
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->first();

        if (! $log) {
            $log = new SearchLog();
            $log->user_id = $user->id;
            $log->type = $type;
            $log->model_id = $model_id;
            $log->query = $query;
            $log->party_size = $party_size;
            $log->save();
        }
    }

    public static function updateReservationStatus(Reservation $reservation, $status, $user_type = 'normal_user')
    {
        $reservation->load('guest', 'vendor', 'user');
        $user = $reservation->user ? $reservation->user : $reservation->guest;
        $vendor = $reservation->vendor;
        $reservation->status = $status;
        $reservation->save();

        if ($status != 'cancelled' && $status != 'pending' && $status != 'approved') {
            throw new Exception('Invalid reservation status', 403);
        }

        if ($status == 'cancelled') {
            $reservation->approved = false;
            if ($user_type == 'normal_user') {
                $short_text = "$user->first_name $user->last_name just Cancelled his/her Reservation with $vendor->company_name.";
                Admin::sendVendorReservationMsg('Reservation Cancelled', $vendor, $reservation, $short_text);
            } else {
                $short_text = "Your Reservation with $vendor->company_name has been cancelled.";
                Admin::sendReservationMsg('Reservation Cancelled', $user, $reservation, $short_text);
            }
        }

        if ($status == 'pending') {
            $reservation->approved = false;
            if ($user_type == 'normal_user') {
                $short_text = "$user->first_name $user->last_name just set a Reservation with $vendor->company_name  to pending and awaiting approval.";
                Admin::sendVendorReservationMsg('Reservation Set To Pending', $vendor, $reservation, $short_text);
            } else {
                $short_text = "Your Reservation with $vendor->company_name has been set to Pending.";
                Admin::sendReservationMsg('Reservation Set To Pending', $user, $reservation, $short_text);
            }
        }

        if ($user_type != 'normal_user') {
            if ($status == 'approved') {
                $reservation->approved = true;
                $short_text = "Your Reservation with $vendor->company_name has been Approved. Reservation is set for $reservation->formated_date, at $reservation->formated_time";
                $message = "<p>$short_text</p> 
                            <p><span style='font-weight:bold'>Time:</span> $reservation->formated_time </p> 
                            <p><span style='font-weight:bold'>Status:</span> $reservation->status </p> ";
                $message .= $vendor->cancellation_policy ? "<p><span style='font-weight:bold'>Restaurant Cancelation Policy:</span> $vendor->cancellation_policy </p> " : `You can cancel your reservation up until 6 hours before the booked time. We allow 15 mins for arrival, if you don't arrive after 15mins your reservation might no longer be honored`;
                $message .= $vendor->dress_code ? "<p><span style='font-weight:bold'>Dress Code:</span> $vendor->dress_code </p>" : '';
                $message .= " <p><span style='font-weight:bold'>Note:</span> On busy nights & days, active customers have 2hrs to dine due to other reservations</p>
                            <p>Login to view Reservation.</p> 
                            <div class='reservation-div'> 
                            <a href='".route('reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> 
                            </div>";

                if ($user->email) {
                    Mail::to($user->email)->queue(new SendMail(
                        $user->first_name,
                        'Reservation Approved',
                        $message,
                        $vendor->attach_menu_pdf ? $vendor->menu_pdf : null,
                        action_log: [
                            'vendor_id' => $vendor->id,
                            'route' => 'email',
                            'type' => 'reservation_email',
                            'action_by' => 'vendor',
                        ]
                    ));
                }

                $user_phone = $reservation->phone ? $reservation->phone : $user->phone_number;
                $user_phone = $user_phone ? $user_phone : $user->phone;
                if ($user_phone) {
                    ProcessBulkSms::dispatchAfterResponse($user_phone, $user->first_name, $short_text, 'Dinesurf');
                }
            }
            //  else {
            //     $reservation->approved = false;
            //     $short_text = "Your Reservation with $vendor->company_name has been Declined.";
            //     Mail::to($user->email)->queue(new SendMail($user->first_name, "Reservation Declined", "<p>$short_text</p> <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='" . route('reservation', ["id" => $reservation->id]) . "' class='btn reservation-btn'>View Reservation</a> </div>"));
            //
            //     ProcessSms::dispatchAfterResponse($reservation->phone, $user->first_name, $short_text);
            // }
        }

        $reservation->save();
    }

    public static function syncGuests()
    {
        $reservations = Reservation::where('synced_with_guests', false)->get();

        foreach ($reservations as $key => $reservation) {
            self::logGuest($reservation);
        }

        $invitations = Invitation::where('status', 'accepted')->where('synced_with_guests', false)->get();

        foreach ($invitations as $key => $invitation) {
            $user = User::where('email', $invitation->email)->first();
            $reservation = $invitation->reservation;

            if ($user) {
                $guest = Guest::where('email', $user->email)->where('vendor_id', $reservation->vendor_id)->first();

                if (! $guest) {
                    $guest = new Guest();
                }

                $guest->vendor_id = $reservation->vendor_id;
                $guest->user_id = $user->id;
                $guest->first_name = $user->first_name;
                $guest->last_name = $user->last_name;
                $guest->email = $user->email;
                $guest->phone = $user->phone_number;
                $guest->birthday = $user->birthday;
                $guest->save();
            }

            $invitation->synced_with_guests = true;
            $invitation->save();
        }
    }

    public static function logGuest(Reservation $reservation, $newGuest = null)
    {
        $reservation_user = $reservation->user;
        $guest = null;

        if ($reservation_user) {
            $guest = Guest::where('email', $reservation_user->email)->where('vendor_id', $reservation->vendor_id)->first();

            if (! $guest) {
                $guest = new Guest();
            }

            $guest->vendor_id = $reservation->vendor_id;

            $guest->user_id = $reservation_user->id;
            $guest->first_name = $reservation_user->first_name;
            $guest->last_name = $reservation_user->last_name;
            $guest->email = $reservation_user->email;
            $guest->phone = $reservation->phone;
            $guest->birthday = $reservation_user->birthday;
            $guest->save();
        }

        if ($newGuest) {
            $newGuest = (object) $newGuest;
            if ($newGuest->first_name) {
                $guest = $newGuest->email ? Guest::where('email', $newGuest->email)->where('vendor_id', $reservation->vendor_id)->first() : null;

                if (! $guest) {
                    $guest = $newGuest->phone ? Guest::where('phone', $newGuest->phone)->where('vendor_id', $reservation->vendor_id)->first() : null;
                }

                if (! $guest) {
                    $guest = new Guest();
                }

                $guest->vendor_id = $reservation->vendor_id;
                $guest->first_name = $newGuest->first_name;
                if ($newGuest->last_name) {
                    $guest->last_name = $newGuest->last_name;
                }
                if ($newGuest->email) {
                    $guest->email = $newGuest->email;
                }
                if ($newGuest->phone) {
                    $guest->phone = $newGuest->phone;
                }
                $guest->save();
            }
        }

        if ($guest) {
            $reservation->guest_id = $guest->id;
            $reservation->synced_with_guests = true;
            $reservation->save();
        }
    }

    public static function logOnlyGuest($newGuest, $vendor_id)
    {
        $newGuest = (object) $newGuest;

        $guest = $newGuest->email ? Guest::where('email', $newGuest->email)->where('vendor_id', $vendor_id)->first() : null;

        if (! $guest) {
            $guest = $newGuest->phone ? Guest::where('phone', $newGuest->phone)->where('vendor_id', $vendor_id)->first() : null;
        }

        if (! $guest) {
            $guest = new Guest();
        }

        $guest->vendor_id = $vendor_id;
        $guest->first_name = $newGuest->first_name;
        if ($newGuest->last_name) {
            $guest->last_name = $newGuest->last_name;
        }
        if ($newGuest->email) {
            $guest->email = $newGuest->email;
        }
        if ($newGuest->phone) {
            $guest->phone = $newGuest->phone;
        }
        $guest->save();

        return $guest;
    }

    public static function upcomingReservations()
    {
        $query = Reservation::query();
        // return $query->where('date', '>=', now()->toDateString())->where('time', '>=', now()->format("H:i:s"));
        return $query->where('date', '>=', now()->toDateString());
    }

    public static function pastReservationsBefore()
    {
        $query = Reservation::query();

        return $query->where('date', '<', now()->toDateString());
    }

    public static function pastReservations()
    {
        $query = Reservation::query();

        return $query->where('date', '=', now()->toDateString());
    }

    public static function getReservations($vendor, $filters)
    {
        $from = isset($filters->start_date) ? $filters->start_date : null;
        $to = isset($filters->end_date) ? $filters->end_date : null;

        $status = isset($filters->status) ? $filters->status : null;

        $reservation_from = isset($filters->reservation_start_date) ? $filters->reservation_start_date : null;
        $reservation_to = isset($filters->reservation_end_date) ? $filters->reservation_end_date : null;

        $guest_id = isset($filters->guest_id) ? $filters->guest_id : null;

        $query = Reservation::query();

        $type = isset($filters->type) ? $filters->type : null;

        if ($type == 'upcoming') {
            $query = self::upcomingReservations();
        }
        if ($type == 'past') {
            $query = self::pastReservationsBefore();
        }

        $query->where('vendor_id', $vendor->id);

        $query->when($from, function ($query) use ($from) {
            return $query->whereDate('created_at', '>=', $from);
        })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->when($reservation_from, function ($query) use ($reservation_from) {
                return $query->whereDate('date', '>=', $reservation_from);
            })
            ->when($reservation_to, function ($query) use ($reservation_to) {
                return $query->whereDate('date', '<=', $reservation_to);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($guest_id, function ($query) use ($guest_id) {
                return $query->where('guest_id', $guest_id);
            });

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('phone', 'LIKE', '%'.$search_query.'%')
                ->orWhereHas('user', function ($q) use ($search_query) {
                    $q->where('first_name', 'LIKE', '%'.$search_query.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$search_query.'%')
                        ->orWhere('email', 'LIKE', '%'.$search_query.'%');
                })
                ->orWhereHas('guest', function ($q) use ($search_query) {
                    $q->where('first_name', 'LIKE', '%'.$search_query.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$search_query.'%')
                        ->orWhere('email', 'LIKE', '%'.$search_query.'%');
                });
        }

        if (isset($filters->field) && isset($filters->direction)) {
            $query->orderBy($filters->field, $filters->direction);
        } else {
            if ($type) {
                if ($type == 'upcoming') {
                    $query->orderBy('date', 'asc')->orderBy('time', 'asc');
                }
                if ($type == 'past') {
                    $query->orderBy('date', 'desc');
                }
            } else {
                $query->orderBy('id', 'desc');
            }
        }

        if (isset($filters->view)) {
            if ($filters->view == 'calendar') {
                $date = $filters->date ? Carbon::parse($filters->date)->toDateString() : now()->toDateString();
                $query->where('date', $date);
            }
        }

        return $query;
    }

    public static function getGuests(Vendor $vendor, $filters)
    {
        $from = isset($filters->start_date) ? $filters->start_date : null;
        $to = isset($filters->end_date) ? $filters->end_date : null;

        $approved = isset($filters->approved) ? $filters->approved : null;

        $birthday = $filters->birthday == 'today' ? now()->format('d/m') : null;
        $birthdayArray = [];

        if ($filters->birthday == 'date') {
            $birthday = $filters->birthday_date;
        }

        if ($filters->birthday == 'month') {
            $period = CarbonPeriod::create(now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString());

            $birthdayArray = [];
            // Iterate over the period
            foreach ($period as $date) {
                array_push($birthdayArray, $date->format('d/m'));
            }

            // dd($birthdayArray);
        }

        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];

        if (in_array($filters->birthday, $months)) {
            $period = CarbonPeriod::create(Carbon::parse($filters->birthday)->setYear(now()->year)->startOfMonth()->toDateString(), Carbon::parse($filters->birthday)->setYear(now()->year)->endOfMonth()->toDateString());

            $birthdayArray = [];
            // Iterate over the period
            foreach ($period as $date) {
                array_push($birthdayArray, $date->format('d/m'));
            }

            // dd($birthdayArray);
        }

        $query = Guest::query();

        $query->where('vendor_id', $vendor->id);

        if ($approved != null) {
            $approved = ($approved === 'true') ? true : false;
        }

        $query->when($from, function ($query) use ($from) {
            return $query->whereDate('created_at', '>=', $from);
        })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->when(isset($approved), function ($query) use ($approved, $vendor) {
                if ($approved == false) {
                    return $query->whereHas('blockLists', function ($query) use ($vendor) {
                        return  $query->where('vendor_id', $vendor->id);
                    });
                } else {
                    return $query->doesntHave('blockLists');
                }
            })
            ->when($birthday, function ($query) use ($birthday) {
                return $query->where('birthday', $birthday);
            })
            ->when($birthdayArray, function ($query) use ($birthdayArray) {
                return $query->whereIn('birthday', $birthdayArray);
            });

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('phone', 'LIKE', '%'.$search_query.'%')
                ->where('first_name', 'LIKE', '%'.$search_query.'%')
                ->orWhere('last_name', 'LIKE', '%'.$search_query.'%')
                ->orWhere('email', 'LIKE', '%'.$search_query.'%');
        }

        if (isset($filters->field) && isset($filters->direction)) {
            $query->orderBy($filters->field, $filters->direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    public static function getMenus(Vendor $vendor, $filters)
    {
        $from = isset($filters->start_date) ? $filters->start_date : null;
        $to = isset($filters->end_date) ? $filters->end_date : null;

        $query = Menu::query();
        $query->with('menuCategories');
        $query->where('vendor_id', $vendor->id);

        $query->when($from, function ($query) use ($from) {
            return $query->whereDate('created_at', '>=', $from);
        })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            });

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('name', 'LIKE', '%'.$search_query.'%');
        }

        if (isset($filters->field) && isset($filters->direction)) {
            $query->orderBy($filters->field, $filters->direction);
        } else {
            $query->orderBy('order', 'asc');
        }

        return $query;
    }

    public static function getMenuCategories(Vendor $vendor, $filters)
    {
        $from = isset($filters->start_date) ? $filters->start_date : null;
        $to = isset($filters->end_date) ? $filters->end_date : null;

        $query = MenuCategory::query();
        $query->with(['items', 'menus']);
        $query->where('vendor_id', $vendor->id);

        if (isset($filters->menu_id)) {
            $query->whereHas('menus', function ($query) use ($filters) {
                $query->where('menu_id', $filters->menu_id);
            });
        }

        $query->when($from, function ($query) use ($from) {
            return $query->whereDate('created_at', '>=', $from);
        })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            });

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('name', 'LIKE', '%'.$search_query.'%');
        }

        if (isset($filters->field) && isset($filters->direction)) {
            $query->orderBy($filters->field, $filters->direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    public static function getMenuCategoryItems(Vendor $vendor, $filters)
    {

        // since menu and items does not have any relationship, i have to first fetch all the menu categories and fetch only  items for all the categories.
        $cateFilter = ['menu_id' => $filters->menu_id];
        $categoriesList = self::getMenuCategories($vendor, $filters);
        $result = $categoriesList->get();
        $ids = [];
        foreach ($result as $item) {
            array_push($ids, $item->id);
        }

        $from = isset($filters->start_date) ? $filters->start_date : null;
        $to = isset($filters->end_date) ? $filters->end_date : null;

        $query = MenuCategoryItem::query();
        $query->with('categories');
        $query->where('vendor_id', $vendor->id);

        if (isset($filters->category_id)) {
            $query->whereHas('categories', function ($query) use ($filters) {
                $query->where('menu_category_id', $filters->category_id);
            });
        }

        if (isset($ids)) {
            $query->whereHas('categories', function ($query) use ($ids) {
                $query->whereIn('menu_category_id', $ids);
            });
        }

        $query->when($from, function ($query) use ($from) {
            return $query->whereDate('created_at', '>=', $from);
        })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            });

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('name', 'LIKE', '%'.$search_query.'%')
                ->orWhere('price', 'LIKE', '%'.$search_query.'%');
        }

        if (isset($filters->field) && isset($filters->direction)) {
            $query->orderBy($filters->field, $filters->direction);
        } else {
            $query->orderBy('order', 'asc');
        }

        return $query;
    }

    public static function block(Vendor $vendor, $email)
    {
        $blocked = BlockList::where('email', $email)->where('vendor_id', $vendor->id)->first();
        if (! $blocked) {
            $blocked = new Blocklist;
            $blocked->vendor_id = $vendor->id;
            $blocked->email = $email;
            $blocked->save();
        }
    }

    public static function unBlock(Vendor $vendor, $email)
    {
        $blocked = BlockList::where('email', $email)->where('vendor_id', $vendor->id)->first();
        if ($blocked) {
            $blocked->delete();
        }
    }

    public static function isBlocked($block_list, $email)
    {
        $blocked = false;
        foreach ($block_list as $key => $value) {
            if ($value->email == $email) {
                $blocked = true;
            }
        }

        return $blocked;
    }

    public static function updateReviews()
    {
        $vendors = Vendor::get();

        $size = $vendors->count();

        print_r("\nupdating reviews for $size vendors...\n");

        foreach ($vendors as $key => $vendor) {
            try {
                self::updateVendorReviews($vendor);
            } catch (\Throwable $th) {
                // throw $th;
                Log::error($th);
            }
        }

        print_r("\nDone updating reviews\n");
    }

    public static function updateVendorReviews($vendor)
    {
        // Trip Advisor Reviews
        if ($vendor->trip_advisor_id) {
            $reviews = null;
            try {
                $reviews = TripAdvisor::getReviews($vendor->trip_advisor_id);
            } catch (\Throwable $th) {
                // throw $th;
                Log::error($th);
            }

            if ($reviews) {
                $reviews = $reviews->data ? $reviews->data : [];

                $reviews_size = count($reviews);

                print_r("\nFound $reviews_size tripadvisor reviews for vendor id: $vendor->id\n");

                foreach ($reviews as $key => $model) {
                    $review = Review::where('trip_advisor_review_id', $model->id)->first();

                    if (! $review) {
                        if ($model->user->username) {
                            $newReview = new Review();
                            $newReview->vendor_id = $vendor->id;
                            $newReview->trip_advisor_review_id = $model->id;
                            $newReview->type = 'vendor';
                            $newReview->content = $model->title.".\n".$model->text;
                            $newReview->name = $model->user->username;
                            $newReview->avatar = $model->user->avatar->small->url;
                            $newReview->stars = $model->rating;
                            $newReview->created_at = Carbon::parse($model->published_date)->toDateTimeString();
                            $newReview->updated_at = Carbon::parse($model->published_date)->toDateTimeString();
                            $newReview->save();
                        }
                    }
                }
            }
        }

        // Google Reviews
        $reviews = null;
        try {
            $reviews = GoogleReviews::getReviews($vendor->id);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error($th);
        }

        if ($reviews) {
            if (count($reviews) > 0) {
                $reviews_size = count($reviews);

                print_r("\nFound $reviews_size google reviews for vendor id: $vendor->id\n");

                foreach ($reviews as $key => $review) {
                    $greview = Review::where('google_review_id', $review->id)->first();

                    if (! $greview) {
                        $timestamp = $review->time;
                        $dateTime = new DateTime("@$timestamp");
                        $dateTime->setTimezone(new DateTimeZone('UTC'));
                        $date = $dateTime->format('Y-m-d H:i:s');

                        $model = new Review();
                        $model->vendor_id = $vendor->id;
                        $model->google_review_id = $review->id;
                        $model->type = 'vendor';
                        $model->content = $review->text;
                        $model->name = $review->author_name;
                        $model->stars = $review->rating;
                        $model->avatar = $review->profile_photo_url;
                        $model->created_at = Carbon::parse($date)->toDateTimeString();
                        $model->updated_at = Carbon::parse($date)->toDateTimeString();
                        $model->save();
                    }
                }
            }
        }
    }

    public static function getUserSearchHistory($user = null)
    {
        $history = [];

        if ($user) {
            $history = SearchLog::where('user_id', $user->id)->whereHas('vendor', function ($q) {
                $q->where('visible', true);
            })->orderBy('id', 'DESC')->take(5);
        }

        return $history;
    }

    public static function getMonths()
    {
        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        return json_encode($month);
    }

    public static function events(Vendor $vendor, $filters)
    {
        $query = Event::query();
        $query->with('images');
        $query->where('vendor_id', $vendor->id);

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('name', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('description', 'LIKE', '%'.$search_query.'%');
        }

        if (isset($filters->sort)) {
            if ($filters->sort === 'past') {
                $query->where('end_date', '<', now()->toDateString());
            }

            if ($filters->sort === 'upcoming') {
                $query->where('start_date', '>', now()->toDateString());
            }

            if ($filters->sort === 'live') {
                $query->where('start_date', '<=', now()->toDateString());
                $query->where('end_date', '>=', now()->toDateString());
            }
        }
        $query->orderBy('end_date', 'desc');

        return $query;
    }

    public static function setVendorSlug()
    {
        $vendors = Vendor::all();

        foreach ($vendors as $vendor) {
            $vendor->slug = Str::slug($vendor->company_name, '-');
            $vendor->save();
            print_r("\nslug set for $vendor->slug\n");
        }

        return 'done';
    }

    public static function transactions(Vendor $vendor, $filters)
    {
        $query = Transaction::query();

        $query->where('vendor_id', $vendor->id);

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('phone', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('email', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('name', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('reference', 'LIKE', '%'.$search_query.'%');
        }

        if (isset($filters->sort)) {
            $query->orderBy('reference', 'desc');
        }
        $query->orderBy('id', 'desc');

        return $query;
    }

    public static function orders(Vendor $vendor, $filters){
        
        $query = Order::query();
        $query->where('vendor_id', $vendor->id);

        if (isset($filters->search)) {
            $search_query = $filters->search;
            $query->where('name', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('email', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('amount', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('table_number', 'LIKE', '%'.$search_query.'%');
            $query->orWhere('status', 'LIKE', '%'.$search_query.'%');
        }
        

        if($filters->tab === 'in-kitchen'){
            $query->where('status', 'in-progress');
            $query->orWhere('status', 'pending');
        }

        if($filters->tab === 'order-history'){
            $query->where('status', 'completed');
        }

        if($filters->tab === 'invoices'){
            $query->where('status', 'close');
            $query->orWhere('status', 'open');
        }

       

        
        $query->orderBy('id', 'desc');
        return $query;

    }
}

return new General;
