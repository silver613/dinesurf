<?php

namespace App\Services\AllServices;

use App\Jobs\ProcessBulkSms;
use App\Jobs\ProcessWhatsapp;
use App\Mail\SendMail;
use App\Mail\VendorWelcome;
use App\Models\Card;
use App\Models\Country;
use App\Models\Event;
use App\Models\Guest;
use App\Models\MailingList;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use App\Models\Plan;
use App\Models\PlanFrequency;
use App\Models\Reservation;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Voucher;
use App\Models\VoucherUsageLog;
use App\Services\PaymentMethods\Paystack;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Admin
{
    public static function subscribe($type, $id, Plan $plan, $reference, PlanFrequency $frequency)
    {
        $check = Subscription::where($type.'_id', $id)->where('active', true)->first();
        if ($check) {
            throw new Exception('Already Subscribed');
        }

        $subscription = new Subscription();
        $subscription->plan_id = $plan->id;
        $subscription->plan_frequency_id = $frequency->id;
        $subscription->active = true;
        if ($type == 'user') {
            $subscription->user_id = $id;
        }
        if ($type == 'vendor') {
            $subscription->vendor_id = $id;
        }
        $subscription->plan_start = now()->startOfDay()->toDateTimeString();
        $subscription->plan_end = now()->addDays($frequency->duration)->startOfDay()->toDateTimeString();
        $subscription->transaction_reference = $reference;
        $subscription->save();

        if ($type == 'user') {
            $user = User::find($id);
            $user->subscribed = true;
            $user->plan_id = $plan->id;
            $user->plan_frequency_id = $frequency->id;
            $user->save();
        }

        if ($type == 'vendor') {
            $vendor = Vendor::find($id);
            $vendor->subscribed = true;
            $vendor->plan_id = $plan->id;
            $vendor->plan_frequency_id = $frequency->id;
            $vendor->save();
        }

        $vendor->free_trial = false;
        $vendor->free_trial_start = null;
        $vendor->free_trial_end = null;
        $vendor->verified = true;
        $vendor->save();

        $msg = "Thank you for subscribing to Dinesurf. <br> Proceed to login and enjoy Dinesurf's full capabilities. <a href='https://app.dinesurf.com/vendors'>Click Here</a>";
        Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, 'Subscription Successful', $msg, action_log: [
            'vendor_id' => $vendor->id,
            'route' => 'email',
            'type' => 'subcription_email',
            'action_by' => 'dinesurf',
        ]));

        return $subscription;
    }

    public static function unSubscribe(Subscription $subscription)
    {
        $subscription->active = false;
        $subscription->save();

        $vendor = $subscription->vendor;
        $vendor->subscribed = false;
        $vendor->verified = false;
        $vendor->save();
    }

    public static function storeAuthCode($model_id, $id, $response)
    {
        $data = (object) $response->data;

        $payment_method = Card::where($model_id, $id)->first();

        if (! $payment_method) {
            // throw new Exception("Payment Method Already Added");
            $payment_method = new Card;
            $payment_method->$model_id = $id;
            $model = null;

            // if ($model_id == "vendor_id") {
            //     $model = Vendor::find($id);
            // }

            // if ($model_id == "user_id") {
            //     $model = User::find($id);
            // }
        }

        $payment_method->bank_name = $data->authorization->bank;
        $payment_method->token = $data->authorization->authorization_code;
        $payment_method->auth = json_encode($data->authorization);
        $payment_method->primary = true;
        $payment_method->card_type = $data->authorization->card_type;
        $payment_method->exp_year = $data->authorization->exp_year;
        $payment_method->exp_month = $data->authorization->exp_month;
        $payment_method->email = $data->customer ? $data->customer->email : null;
        $payment_method->last_digits = $data->authorization->last4;
        $payment_method->save();

        return $payment_method;
    }

    public static function checkVoucher($code, $type, $model = null)
    {
        if ($type == 'vendor' && ! $model) {
            $model = session('current_vendor');
        }

        if ($type == 'user' && ! $model) {
            $model = Auth::user();
        }

        // dd($code, $type, $model);

        if (! $model) {
            throw new Exception('You need to be logged in to apply voucher/coupon', 401);
        }

        $general_error = new Exception('Voucher code has been used to its limit or is not valid.');

        $voucher = Voucher::where('code', $code)->first();
        // echo $voucher;
        if (! $voucher) {
            throw $general_error;
        }

        if (! $voucher->status) {
            throw $general_error;
        }

        if ($voucher->uses && $voucher->max_uses) {
            if ((int) $voucher->uses >= (int) $voucher->max_uses) {
                throw $general_error;
            }
        }

        // if ($voucher->quantity_required) {
        //     if ($user->cart) {
        //         if ($user->cart->cartItems->count() < (int) $voucher->quantity_required) {
        //             return response()->json([
        //                 "data" => [],
        //                 "success" => false,
        //                 "type" => "not_found",
        //                 "message" => "You need to order a minimum of " . $voucher->quantity_required . " products before you can use this Voucher code",
        //             ], 200);
        //         }
        //     }
        // }

        if ($voucher->users) {
            if ($voucher->users->count() > 0) {
                $users = $voucher->users->pluck('id')->toArray();
                if (! in_array($model->id, $users)) {
                    throw   new Exception("You're not authorized to use this voucher");
                }
            }
        }

        if ($voucher->vendors) {
            if ($voucher->vendors->count() > 0) {
                $vendors = $voucher->vendors->pluck('id')->toArray();
                if (! in_array($model->id, $vendors)) {
                    throw new Exception("You're not authorized to use this voucher");
                }
            }
        }

        $used_by_model = null;

        if ($type == 'vendor') {
            $used_by_model = VoucherUsageLog::where('vendor_id', $model->id)->where('voucher_id', $voucher->id)->where('used', true)->first();
        }

        if ($type == 'user') {
            $used_by_model = VoucherUsageLog::where('user_id', $model->id)->where('voucher_id', $voucher->id)->where('used', true)->first();
        }

        if ($used_by_model) {
            throw new Exception("You've used this voucher before");
        }

        if ($voucher->starts_at) {
            if (now()->toDateTimeString() < $voucher->starts_at) {
                throw  new Exception('Voucher code is not yet valid. Will be valid at '.Carbon::now()->format('d-M-Y h:i:sa'));
            }
        }

        if ($voucher->expires_at) {
            if (now()->toDateTimeString() > $voucher->expires_at) {
                throw new Exception('Voucher code has expired');
            }
        }

        return $voucher;
    }

    public static function applyVoucher($voucher_id, $type, $usage_type, $use, $model = null)
    {
        $voucher = Voucher::find($voucher_id);

        if ($type == 'vendor' && ! $model) {
            $model = session('current_vendor');
        }

        if ($type == 'user' && ! $model) {
            $model = Auth::user();
        }

        if (! $model) {
            throw new Exception('You need to be logged in to apply voucher/coupon', 401);
        }

        if ($type == 'vendor') {
            $usage = VoucherUsageLog::where('vendor_id', $model->id)->where('voucher_id', $voucher_id)->first();
        }

        if ($type == 'user') {
            $usage = VoucherUsageLog::where('user_id', $model->id)->where('voucher_id', $voucher_id)->first();
        }

        if (! $usage) {
            $usage = new VoucherUsageLog;
            if ($type == 'vendor') {
                $usage->vendor_id = $model->id;
            }
            if ($type == 'user') {
                $usage->user_id = $model->id;
            }

            $usage->voucher_id = $voucher->id;
            $usage->usage_type = $usage_type;
            $voucher->uses = $usage->used ? $voucher->uses + 1 : $voucher->uses;
            $voucher->save();
        }
        $usage->used = $usage->used ? true : $use;
        $usage->save();
    }

    public static function checkSubscriptions()
    {
        $subscriptions = Subscription::where('plan_end', '<=', now()->startOfDay()->toDateString())
            ->where('active', true)
            ->with('plan')
            ->with('vendor', function ($query) {
                $query->with('card');
            })->get();

        foreach ($subscriptions as $key => $sub) {
            if ($sub->vendor->subscribed) {
                self::unSubscribe($sub);
                self::chargeAndSubscribe($sub);
            }
        }

        $vendors = Vendor::where('free_trial', true)->where('free_trial_end', '<=', now()->startOfDay()->toDateString())
            ->get();

        foreach ($vendors as $key => $vendor) {
            $vendor->free_trial = false;
            // $vendor->free_trial_start = null;
            // $vendor->free_trial_end = null;
            $vendor->save();

            $msg = "Your Dinesurf Free trial has ended. <br> Please proceed to login and susbcribe to enjoy Dinesurf's full capabilities. <a href='https://app.dinesurf.com/vendors'>Click Here</a>";
            Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, 'Free trial Ended', $msg, action_log: [
                'vendor_id' => $vendor->id,
                'route' => 'email',
                'type' => 'subcription_email',
                'action_by' => 'dinesurf',
            ]));
        }

        return 'Subcription checks done';
    }

    public static function chargeAndSubscribe($sub)
    {
        $check = Subscription::where('vendor_id', $sub->vendor_id)->where('active', true)->first();
        if ($check) {
            throw new Exception('Already Subscribed');
        }

        $vendor = $sub->vendor;
        $plan = $vendor->plan ? $vendor->plan : $sub->plan;
        $frequency = $vendor->planFrequency ? $vendor->planFrequency : $sub->planFrequency;
        $user = $sub->user;
        $card = $vendor->card;

        if ($plan && $frequency && $card) {
            $voucher = null;
            $amount = $frequency->price;

            if ($vendor && $vendor->renew_subscription) {
                $useable_voucher_log = VoucherUsageLog::where('vendor_id', $vendor->id)->where('usage_type', 'subscription')->where('used', false)->with('voucher')->first();
                if ($useable_voucher_log) {
                    $voucher = $useable_voucher_log->voucher;
                    if ($voucher) {
                        $code = $voucher->code;
                        $voucher = null;
                        try {
                            $voucher = self::checkVoucher($code, 'vendor', $vendor);
                        } catch (\Throwable $th) {
                            // throw $th;
                            Log::error($th);
                        }
                    }

                    if ($voucher) {
                        if ($voucher->type == 'percentage') {
                            $amount_off = $amount * ($voucher->discount_amount / 100);
                            $amount = $amount - $amount_off;
                        }
                        if ($voucher->type == 'price') {
                            $amount = $amount - $voucher->discount_amount;
                        }
                    }
                }

                try {
                    $response = Paystack::charge($amount, $card->email ? $card->email : $vendor->email, $card->token);
                } catch (\Throwable $th) {
                    throw $th;
                    // Log::error($th);
                }

                if (isset($response)) {
                    if ($response->data) {
                        if ($response->data->reference) {
                            try {
                                $txn = TxnService::createTransaction($amount, $vendor->first_name.' '.$vendor->last_name, $vendor->phone_number, $vendor->email, 'subscription', $plan->currency, 'paystack', null, $vendor->id, $voucher ? $voucher->id : null, $response->data->status, $response->data->reference, $plan->id, $frequency->id);
                            } catch (\Throwable $th) {
                                // throw $th;
                                Log::error($th);
                            }
                        }

                        if ($response->data->status == 'success') {
                            if ($voucher) {
                                try {
                                    self::applyVoucher($voucher->id, 'vendor', 'subscription', true, $vendor);
                                } catch (\Throwable $th) {
                                    // throw $th;
                                    Log::error($th);
                                }
                            }
                            self::subscribe('vendor', $vendor->id, $plan, $response->data->reference, $frequency);
                        }
                    }
                }
            } else {
                self::unSubscribe($sub);
            }
        } else {
            Log::error("Card not found to re-subscribe subscription for vendor id $vendor->id");
        }

        print_r("\nCharge and subscrption renewal for $vendor->company_name complete\n");
    }

    public static function renewSubById($id)
    {
        $sub = Subscription::findOrFail($id);
        print_r("\nCharge and subscrption for sub_id $id started\n");

        return self::chargeAndSubscribe($sub);
    }

    public static function updateTransactions()
    {
        $transactions = Transaction::where('status', '!=', 'success')->where('status', '!=', 'failed')->get();

        foreach ($transactions as $key => $txn) {
            if ($txn->payment_method == 'paystack') {
                try {
                    $res = Paystack::verify($txn->reference);
                } catch (\Throwable $th) {
                    Log::error($th);
                    // throw $th;
                }

                if (isset($res)) {
                    if ($res->data) {
                        if ($res->data->reference) {
                            try {
                                $txn = TxnService::updateTransactionStatus($res->data->reference, $res->data->status);
                            } catch (\Throwable $th) {
                                // throw $th;
                                Log::error($th);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function createReservation($vendor_id, $party_size, $date, $time, $user_id = null, $guest_id = null, $created_by_vendor = false, $seating_preferences = [], $phone = null, $note = null, $source = null, $newGuest = null, $campaign_id = null)
    {
        if ($newGuest) {
            try {
                $guest = General::logOnlyGuest($newGuest, $vendor_id);
            } catch (\Throwable $th) {
                Log::error($th);
                throw $th;
            }
        }

        $vendor = Vendor::find($vendor_id);
        $reservation = new Reservation();

        $reservation->user_id = $user_id;
        if (isset($guest)) {
            $reservation->guest_id = $guest->id;
        }
        if ($guest_id) {
            $reservation->guest_id = $guest_id;
        }
        $reservation->vendor_id = $vendor_id;
        $reservation->party_size = $party_size;
        $reservation->phone = $phone;
        $reservation->created_by_vendor = $created_by_vendor;
        $reservation->date = $date;
        $reservation->note = $note;
        $reservation->source = $source;
        $reservation->campaign_id = $campaign_id;
        // $reservation->start_time = $start_time;
        // $reservation->end_time = $end_time;
        $reservation->time = $time;
        $seating_preferences = implode(', ', (array) $seating_preferences);
        // $seating_preferences = substr($seating_preferences, 1);
        $reservation->seating_preferences = $seating_preferences;

        if (! $created_by_vendor) {
            $reservation->deal_id = $vendor->latestDeal ? $vendor->latestDeal->id : null;
        }

        $reservation->save();

        try {
            General::logGuest($reservation, $newGuest);
        } catch (\Throwable $th) {
            Log::error($th);
            throw $th;
        }

        if (! $reservation->created_by_vendor) {
            ProcessWhatsapp::dispatchAfterResponse($reservation, 'created', 'vendor', [
                'vendor_id' => $vendor->id,
                'route' => 'whatsapp',
                'type' => 'vendor_whatsapp_notification',
            ]);
        }

        $title = "A Reservation was created for $vendor->comapny_name";
        $msg = "<p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='https://app.dinesurf.com/admin/resources/reservations' class='btn reservation-btn'>View Reservation</a> </div>";

        Mail::to('martins@dinesurf.com')->queue(new SendMail('Martins', $title, $msg, action_log: [
            'route' => 'email',
            'type' => 'reservation_email',
            'action_by' => 'dinesurf',
        ]));
        Mail::to('joshua@dinesurf.com')->queue(new SendMail('Joshua', $title, $msg, action_log: [
            'route' => 'email',
            'type' => 'reservation_email',
            'action_by' => 'dinesurf',
        ]));
        Mail::to('itohan.ugberaese@gmail.com')->queue(new SendMail('Itohan', $title, $msg, action_log: [
            'route' => 'email',
            'type' => 'reservation_email',
            'action_by' => 'dinesurf',
        ]));

        if ($created_by_vendor) {
            try {
                General::updateReservationStatus($reservation, 'approved', 'vendor');
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return $reservation;
    }

    public static function createGuest($vendor_id, $first_name, $last_name, $email, $phone, $birthday = null, $seating_preferences = [], $user_id = null, $general_notes = null, $food_drink_preferences = null, $source = null)
    {
        $guest = new Guest();
        $guest->user_id = $user_id;
        $guest->vendor_id = $vendor_id;
        $guest->first_name = $first_name;
        $guest->last_name = $last_name;
        $guest->email = $email;
        $guest->phone = $phone;
        $guest->source = $source;
        $guest->birthday = $birthday;
        $guest->general_notes = $general_notes;
        $seating_preferences = implode(', ', $seating_preferences);
        $guest->seating_preferences = $seating_preferences;
        $guest->food_drink_preferences = $food_drink_preferences;
        $guest->save();

        return $guest;
    }

    public static function updateGuest(Guest $guest, $first_name, $last_name, $email, $phone, $birthday = null, $seating_preferences = [], $user_id = null, $general_notes = null, $food_drink_preferences = null, $source = null)
    {
        $guest->user_id = $user_id;
        $guest->first_name = $first_name;
        $guest->last_name = $last_name;
        $guest->email = $email;
        $guest->phone = $phone;
        $guest->source = $source;
        $guest->birthday = $birthday;
        $guest->general_notes = $general_notes;
        $seating_preferences = implode(', ', $seating_preferences);
        $guest->seating_preferences = $seating_preferences;
        $guest->food_drink_preferences = $food_drink_preferences;
        $guest->save();

        return $guest;
    }

    public static function updateReservation(Reservation $reservation, $party_size, $date, $time, $seating_preferences = [], $phone = null, $note = null, $source = null)
    {
        $reservation->party_size = $party_size;
        $reservation->phone = $phone;
        $reservation->note = $note;
        $reservation->source = $source;
        $reservation->date = $date;
        $reservation->time = $time;
        $seating_preferences = implode(', ', (array) $seating_preferences);
        // $seating_preferences = substr($seating_preferences, 1);
        $reservation->seating_preferences = $seating_preferences;
        $reservation->save();

        return $reservation;
    }

    public static function remindReservationsIn30Mins()
    {
        $reservations_due_in_30_mins = Reservation::approved()->where('date', now()->toDateString())->where('time', now()->addMinutes(30)->format('H:i'))->get();

        foreach ($reservations_due_in_30_mins as $key => $reservation) {
            $user = $reservation->user ? $reservation->user : $reservation->guest;
            $vendor = $reservation->vendor;
            if ($user && $vendor) {
                $short_text = "Your Reservation with $vendor->company_name is due in 30 mins, get ready.";
                self::sendReservationMsg('Reservation Reminder', $user, $reservation, $short_text);
            }
        }
    }

    public static function remindReservationsIn1Hour()
    {
        $reservations_due_in_1_hour = Reservation::approved()->where('date', now()->toDateString())->where('time', now()->addHour()->format('H:i'))->get();

        foreach ($reservations_due_in_1_hour as $key => $reservation) {
            $user = $reservation->user ? $reservation->user : $reservation->guest;
            $vendor = $reservation->vendor;
            if ($user && $vendor) {
                $short_text = "Your Reservation with $vendor->company_name is due in 1 hour, get ready.";
                self::sendReservationMsg('Reservation Reminder', $user, $reservation, $short_text);
            }
        }
    }

    public static function remindReservationsIn1Day()
    {
        $reservations_due_in_1_day = Reservation::approved()->where('date', now()->addDay()->toDateString())->get();

        foreach ($reservations_due_in_1_day as $key => $reservation) {
            $user = $reservation->user ? $reservation->user : $reservation->guest;
            $vendor = $reservation->vendor;
            if ($user && $vendor) {
                $short_text = "Your Reservation with $vendor->company_name is due in 1 day, get ready.";
                self::sendReservationMsg('Reservation Reminder', $user, $reservation, $short_text);
            }
        }
    }

    public static function remindReservationsIn2Days()
    {
        $reservations_due_in_2_days = Reservation::approved()->where('date', now()->addDays(2)->toDateString())->get();

        foreach ($reservations_due_in_2_days as $key => $reservation) {
            $user = $reservation->user ? $reservation->user : $reservation->guest;
            $vendor = $reservation->vendor;
            if ($user && $vendor) {
                $short_text = "Your Reservation with $vendor->company_name is due in 2 days, get ready.";
                self::sendReservationMsg('Reservation Reminder', $user, $reservation, $short_text);
            }
        }
    }

    public static function sendReservationMsg($subject, $user, $reservation, $short_text)
    {
        $vendor = $reservation->vendor;
        if ($user->email) {
            Mail::to($user->email)->queue(new SendMail($user->first_name, $subject, "<p>$short_text</p> <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", $vendor->attach_menu_pdf ? $vendor->menu_pdf : null, action_log: [
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'route' => 'email',
                'type' => 'reservation_email',
                'content' => $short_text,
                'action_by' => 'vendor',
            ]));
        }

        $user_phone = $reservation->phone ? $reservation->phone : $user->phone_number;
        $user_phone = $user_phone ? $user_phone : $user->phone;
        if ($user_phone) {
            ProcessBulkSms::dispatchAfterResponse($user_phone, $user->first_name, $short_text, 'Dinesurf', [
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'route' => 'sms',
                'type' => 'reservation_text',
                'content' => $short_text,
                'action_by' => 'vendor',
            ]);
        }
    }

    public static function sendVendorReservationMsg($subject, $vendor, $reservation, $short_text)
    {
        Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, $subject, "<p>$short_text</p>  <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('vendors.reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", action_log: [
            'vendor_id' => $vendor->id,
            'route' => 'email',
            'type' => 'reservation_email',
            'action_by' => 'vendor',
        ]));
        if ($vendor->company_email && $vendor->company_email != $vendor->email) {
            Mail::to($vendor->company_email)->queue(new SendMail($vendor->company_name, $subject, "<p>$short_text</p>  <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: $reservation->status </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('vendors.reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", action_log: [
                'vendor_id' => $vendor->id,
                'route' => 'email',
                'type' => 'reservation_email',
                'action_by' => 'vendor',
            ]));
        }

        ProcessBulkSms::dispatchAfterResponse($vendor->phone_number, $vendor->company_name, $short_text, 'Dinesurf', [
            'vendor_id' => $vendor->id,
            'route' => 'sms',
            'type' => 'reservation_text',
            'content' => $short_text,
            'action_by' => 'vendor',
        ]);
        if ($vendor->company_phone && $vendor->company_phone != $vendor->phone_number) {
            ProcessBulkSms::dispatchAfterResponse($vendor->company_phone, $vendor->company_name, $short_text, 'Dinesurf', [
                'vendor_id' => $vendor->id,
                'route' => 'sms',
                'type' => 'reservation_text',
                'content' => $short_text,
                'action_by' => 'vendor',
            ]);
        }
    }

    public static function cleanupPhones()
    {
        $models = Reservation::all();
        foreach ($models as $key => $model) {
            if ($model->phone) {
                $phone = $model->phone;
                $phone = substr($phone, -10);
                $phone = '234'.$phone;
                $model->phone = $phone;
                $model->save();
            }
        }

        $models = User::all();
        foreach ($models as $key => $model) {
            if ($model->phone_number) {
                $phone = $model->phone_number;
                $phone = substr($phone, -10);
                $phone = '234'.$phone;
                $model->phone_number = $phone;
                $model->save();
            }
        }

        $models = Guest::all();
        foreach ($models as $key => $model) {
            if ($model->phone) {
                $phone = $model->phone;
                $phone = substr($phone, -10);
                $phone = '234'.$phone;
                $model->phone = $phone;
                $model->save();
            }
        }
    }

    public static function endFreetrial()
    {
        $vendors = Vendor::where('free_trial_end', '<=', now()->startOfDay()->toDateString())
            ->where('free_trial', true)
            ->get();

        print_r("\n".$vendors->count()." vendors on free trial found\n");

        foreach ($vendors as $key => $vendor) {
            $vendor->free_trial = false;
            $vendor->save();

            Mail::to($vendor->email)
                ->queue(new SendMail(
                    $vendor->first_name,
                    'Free Trial Ended',

                    "<p> Thanks for signing up with Dinesurf. We hope you are having a great experience and have been enjoying your free trial.</p>

                    <div style='font-weight:700;'>Unfortunately, your free trial has ended.</div>

                    <p>We'd love to keep you as a customer and there is still time to complete your subscription. Simply visit dinesurf.com or send us an email to subscribe.</p>

                    <p>We'd also love to have a chat with you on how your trial period went, so we can provide the necessary support, you can book a virtual session <a href='https://calendly.com/martinsudotai/restaurant-feedback'>here</a>, or you can let us know your availability and a team member will visit your restaurant for the session.</p>

                    <p>As a reminder, now that your free trial has expired, you would not be able to use certain features on our platform. If you have any more questions or feedback, just send us a mail and we will get back to you.</p>
                    
                    <div class='reservation-div'> <a href='".route('vendors.billing')."' class='btn reservation-btn'>Renew Subscription</a> </div>

                    ",
                    action_log: [
                        'vendor_id' => $vendor->id,
                        'route' => 'email',
                        'type' => 'subscription_email',
                        'action_by' => 'dinesurf',
                    ]
                ));
        }
    }

    public static function freeTrialAlmostEndedNotification()
    {
        $no_of_days = 2;
        $vendors = Vendor::where('free_trial_end', now()->addDays($no_of_days)->toDateString())
            ->where('free_trial', true)
            ->get();

        foreach ($vendors as $key => $vendor) {
            Mail::to($vendor->email)
                ->queue(new SendMail(
                    $vendor->first_name,
                    'Free Trial Almost Ended',

                    "<p> Thanks for signing up with Dinesurf. We hope you are having a great experience and have been enjoying your free trial.</p>
    
             <div style='font-weight:700;'>Unfortunately, your free trial ends soon in ($no_of_days days).</div>
    
             <p>We'd love to keep you as a customer and there is still time to complete your subscription. Simply visit <a href='http://dinesurf.com'>dinesurf.com</a> or send us an email to subscribe.</p>
    
             <p>We'd also love to have a chat with you on how your trial period went, so we can provide the necessary support, you can book a virtual session <a href='https://calendly.com/martinsudotai/restaurant-feedback'>here</a>, or you can let us know your availability and a team member will visit your restaurant for the session.</p>
    
             <p>As a reminder, when your free trial expires, you would not be able to use certain features on our platform. If you have any more questions or feedback, just send us a mail and we will get back to you.</p>
            
             <div class='reservation-div'> <a href='".route('vendors.billing')."' class='btn reservation-btn'>Start Subscription</a> </div>
    
             ",
                    action_log: [
                        'vendor_id' => $vendor->id,
                        'route' => 'email',
                        'type' => 'subscription_email',
                        'action_by' => 'dinesurf',
                    ]
                ));
        }
    }

    public static function sendReviewMails()
    {
        $reservations = Reservation::where('created_at', '=>', now()->subHours(4)->toDateTimeString())
            ->where('created_at', '<=', now()->subHours(3)->toDateTimeString())
            ->where('approved', true)
            ->get();

        foreach ($reservations as $key => $reservation) {
            $user = $reservation->user;
            $guest = $reservation->guest;
            $model = $reservation->user ? $reservation->user : $reservation->guest;
            $vendor = $reservation->vendor;

            if ($model) {
                if ($model->email) {
                    Mail::to($model->email)
                        ->queue(new SendMail(
                            $model->first_name,
                            "😁Help a fellow diner out and leave a review on $vendor->company_name!",

                            "<p>Thanks for letting Dinesurf help you find and experience $vendor->company_name. In today's world online reviews are very important, both for the restaurants you love and diners just like you.</p>
        
                                <p>We at $vendor->company_name are glad you enjoyed your time with us. Please share your experience at $vendor->company_name as a review on Dinesurf and be a guide to another diner.</p>
        
                                <p>Cheers</p>
        
                                <p>The Dinesurf Team.</p>
                                
                                <div class='reservation-div'> <a href='".route('restaurants.index', ['id' => $vendor->id]).'?tab=reviews'."' class='btn reservation-btn'>Leave a Review</a> </div>
        
                             ",
                            action_log: [
                                'user_id' => $user ? $user->id : null,
                                'guest_id' => $guest ? $guest->id : null,
                                'route' => 'email',
                                'type' => 'review_email',
                                'action_by' => 'dinesurf',
                            ]
                        ));
                }
            }
        }

        return 'done';
    }

    public static function resendVendorWelcomeEmail($id)
    {
        $vendor = Vendor::find($id);
        Mail::to($vendor->email)->queue(new VendorWelcome($vendor));

        return "\ndone";
    }

    public static function storeSubscriptionCards()
    {
        $subscriptions = Subscription::all();

        $size = $subscriptions->count();
        echo "\n found $size subscriptions\n";

        foreach ($subscriptions as $key => $sub) {
            try {
                $res = Paystack::verify($sub->transaction_reference);
            } catch (\Throwable $th) {
                Log::error($th);
                echo "\nAn error occured while verifying subscription transaction for subsciption_id $sub->id\n".$th;
            }

            $card = null;
            $vendor_id = Transaction::where('reference', $sub->transaction_reference)->value('vendor_id');

            try {
                $card = self::storeAuthCode('vendor_id', $vendor_id, $res);
            } catch (\Throwable $th) {
                Log::error($th);
                echo "\nAn error occured while storing card for subsciption_id $sub->id\n".$th;
            }

            if ($card) {
                $msg = "\nCard with card_id $card->id for vendor_id $vendor_id was created and stored\n";
                Log::info($msg);
                echo $msg;
            }
        }

        return "\ndone\n";
    }

    public static function storeCard($vendor_id, $txn_id)
    {
        try {
            $res = Paystack::verify($txn_id);
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            $card = self::storeAuthCode('vendor_id', $vendor_id, $res);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }

        print_r("\nCard with id: $card->id stored for vendor id: $vendor_id\n");

        return $card;
    }

    public static function syncVendorPlanIds()
    {
        $subscriptions = Subscription::with('plan')
            ->with('vendor')->get();

        foreach ($subscriptions as $key => $sub) {
            if ($sub->plan && $sub->vendor) {
                $vendor = $sub->vendor;
                $vendor->plan_id = $sub->plan->id;
                $vendor->plan_frequency_id = $sub->planFrequency->id;
                $vendor->save();
            }
        }

        return "\ndone\n";
    }

    public static function addToMailingList($email)
    {
        $mail = MailingList::where('email', $email)->first();

        if ($mail) {
            throw new Exception('Mail Already Added', 402);
        }

        $mail = new MailingList;
        $mail->email = $email;
        $mail->save();

        return $mail;
    }

    public static function syncVendorRoles()
    {
        $vendors = Vendor::all();

        foreach ($vendors as $key => $vendor) {
            self::syncSingleVendorRoles($vendor);
        }
    }

    public static function createMenu($vendor_id, $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = FileService::storeFile($request->file('image'), 'menu_images');
        }
        $menu = new Menu();
        $menu->vendor_id = $vendor_id;
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->image = $path;
        $menu->save();

        return $menu;
    }

    public static function updateMenu(Menu $menu, $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = FileService::storeFile($request->file('image'), 'menu_images');
        }
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->image = $request->hasFile('image') ? $path : $menu->image;
        $menu->save();

        return $menu;
    }

    // menuCategory
    public static function createMenuCategory($vendor_id, $request)
    {
        if (is_array($request?->name)) {
            foreach ($request->name as $key => $value) {
                $menuCat = new MenuCategory();
                $menuCat->vendor_id = $vendor_id;
                $menuCat->name = $value;
                $menuCat->description = $request->description;
                $menuCat->save();

                $menus = Menu::find($request->menu_ids);
                $menuCat->menus()->attach($menus);
            }

            return;
        }

        $menuCat = new MenuCategory();
        $menuCat->vendor_id = $vendor_id;
        $menuCat->name = $request->name;
        $menuCat->description = $request->description;
        $menuCat->save();

        $menus = Menu::find($request->menu_ids);
        $menuCat->menus()->attach($menus);

        return $menuCat;
    }

    public static function updateMenuCategory(MenuCategory $menuCat, $request)
    {
        $menuCat->name = $request->name;
        $menuCat->description = $request->description;
        $menuCat->save();

        $menus = Menu::find($request->menu_ids);
        $menuCat->menus()->detach($request->selectedMenus);
        $menuCat->menus()->attach($menus);

        return $menuCat;
    }

    // menu items

    public static function createMenuCategoryItem($vendor_id, $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = FileService::storeFile($request->file('image'), 'menu_item_images');
        }

        if (is_array($request?->name)) {
            $names = $request->name;
            $prices = $request->price;
            $items = array_combine($names, $prices);
            foreach ($items as $key => $value) {
                $menuCatItem = new MenuCategoryItem();
                $menuCatItem->vendor_id = $vendor_id;
                $menuCatItem->name = $key;
                $menuCatItem->price = $value ?? null;
                $menuCatItem->save();

                $categories = MenuCategory::findMany($request->category_ids);
                $menuCatItem->categories()->attach($categories);
            }

            return;
        }

        $menuCatItem = new MenuCategoryItem();
        $menuCatItem->vendor_id = $vendor_id;
        $menuCatItem->name = $request->name;
        $menuCatItem->price = $request->price;
        $menuCatItem->save();

        $categories = MenuCategory::findMany($request->category_ids);
        $menuCatItem->categories()->attach($categories);

        return $menuCatItem;
    }

    public static function updateMenuCategoryItem(MenuCategoryItem $item, $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = FileService::storeFile($request->file('image'), 'menu_item_images');
        }

        $item->name = $request->name;
        $item->price = $request->price;
        $item->image = $request->hasFile('image') && $path ? $path : $item->image;
        $item->description = $request->description;
        $item->save();

        $categories = MenuCategory::findMany($request->category_ids);
        $item->categories()->detach($request->selectedCategories);
        $item->categories()->attach($categories);

        return $item;
    }

    public static function apiLogin($identifier, $password)
    {
        // Check if user exists
        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        // if User not found
        if (! $user) {
            throw new Exception('email or username not found', 401);
        }

        // if user found, attempt authentication
        Auth::attempt(['email' => $user->email, 'password' => $password]);

        // if authentication failed
        if (! Auth::check()) {
            throw new Exception('invalid login credentials', 401);
        }

        return 'done';
    }

    public static function createEvent($vendor_id, $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = FileService::storeFile($request->file('image'), 'event_images');
        }

        $event = new Event();
        $event->vendor_id = $vendor_id;
        $event->name = $request->name;
        $event->description = $request->description;
        $event->price = $request->price;
        $event->duration = $request->duration;
        $event->capacity = $request->capacity;
        $event->occupancy_goal = $request->goal;
        $event->start_date = $request->startDate;
        $event->start_time = $request->startTime;
        $event->end_date = $request->endDate;
        $event->end_time = $request->endTime;
        $event->image = $path;
        $event->repeat = $request->repeat;
        $event->payment = $request->payment;
        $event->slot = $request->slot;
        $event->packages = $request->packages;
        $event->save();

        return  $event;
    }

    public static function updateEvent(Event $event, $request, $vendor)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = FileService::storeFile($request->file('image'), 'event_images');
        }

        $event->vendor_id = $vendor->id;
        $event->name = $request->name;
        $event->description = $request->description;
        $event->price = $request->price;
        $event->duration = $request->duration;
        $event->capacity = $request->capacity;
        $event->occupancy_goal = $request->goal;
        $event->start_date = $request->startDate;
        $event->start_time = $request->startTime;
        $event->end_date = $request->endDate;
        $event->end_time = $request->endTime;
        $event->image = $request->hasFile('image') ? $path : $event->image;
        $event->repeat = $request->repeat;
        $event->payment = $request->payment;
        $event->slot = $request->slot;
        $event->packages = $request->packages;
        $event->save();

        return  $event;
    }

    public static function createVendorTeams()
    {
        $vendors = Vendor::all();
        foreach ($vendors as $key => $vendor) {
            self::createTeam($vendor);
        }
        print_r("\ncreated vendor teams\n");
    }

    public static function createTeam($vendor)
    {
        $admin_vendor = DB::table('admin_vendor')->where('vendor_id', $vendor->id)->first();
        $admin = $admin_vendor ? DB::table('admins')->where('id', $admin_vendor->admin_id)->first() : null;

        if ($admin) {
            $user = User::where('email', $admin->email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => "$admin->first_name $admin->last_name",
                    'first_name' => $admin->first_name,
                    'last_name' => $admin->last_name,
                    'email' => $admin->email,
                    'password' => $admin->password,
                ]);
            }

            $vendor->user_id = $user->id;
            $vendor->save();

            $team = new Team();
            $team->id = $vendor->id;
            $team->user_id = $user->id;
            $team->name = $vendor->company_name;
            $team->personal_team = true;
            $team->save();

            return $team;
        } else {
            self::createVendorTeam($vendor);
        }
    }

    public static function createVendorTeam($vendor)
    {
        if ($vendor->user_id) {
            $team = new Team();
            $team->id = $vendor->id;
            $team->user_id = $vendor->user_id;
            $team->name = $vendor->company_name;
            $team->personal_team = true;
            $team->save();
        } else {
            $user = User::where('email', $vendor->email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => "$vendor->first_name $vendor->last_name",
                    'first_name' => $vendor->first_name,
                    'last_name' => $vendor->last_name,
                    'email' => $vendor->email,
                    'password' => $vendor->password,
                ]);
            }

            $vendor->user_id = $user->id;
            $vendor->save();

            $team = new Team();
            $team->id = $vendor->id;
            $team->user_id = $user->id;
            $team->name = $vendor->company_name;
            $team->personal_team = true;
            $team->save();
        }

        return $team;
    }

    public static function authResponse($type = 'login', $intendedUrl = null)
    {
        $user = auth()->user();

        if ($user->ownedTeams->count() == 1 && $user->team && $user->vendor) {
            $vendor = $user->vendor;
            session(['current_vendor' => $vendor]);
            if (! auth()->user()->switchTeam($user->team)) {
                abort(403);
            }
        }

        if (session('auth_type') == 'vendor') {
            return redirect('vendors/dashboard');
        } else {
            session()->flash('flash.banner', "$type Successful");
            session()->flash('flash.bannerStyle', 'success');
            session()->flash('flash.autoClose', true);

            if ($intendedUrl) {
                return redirect($intendedUrl);
            } else {
                return redirect('dashboard');
            }
        }
    }

    public static function authRevamp()
    {
        self::createVendorTeams();
        Artisan::call('migrate --path=/database/migrations/later/2022_12_26_114008_drop_more_useless_tables.php');

        return 'auth-revamp setup done';
    }

    public static function ensureUserNames()
    {
        $users = User::whereNull('name')->get();

        $count = $users->count();

        print_r("\n found $count users where name is null\n");

        foreach ($users as $key => $user) {
            $user->name = $user->first_name.' '.$user->last_name;
            $user->save();
        }

        return 'Done';
    }

    public static function syncVendorTeams()
    {
        $vendors = Vendor::all();

        foreach ($vendors as $key => $vendor) {
            $team = Team::find($vendor->id);
            if (! $team) {
                self::createVendorTeam($vendor);
            }
        }

        return 'done';
    }

    public static function setCountryCodes()
    {
        $countries = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, the Democratic Republic of the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => "Cote D'Ivoire",
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => "Korea, Democratic People's Republic of",
            'KR' => 'Korea, Republic of',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => "Lao People's Democratic Republic",
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia, the Former Yugoslav Republic of',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States of',
            'MD' => 'Moldova, Republic of',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'CS' => 'Serbia and Montenegro',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan, Province of China',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania, United Republic of',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.s.',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];

        print_r("\nsetting country codes...\n");

        foreach ($countries as $code => $name) {
            $country = Country::whereName($name)->first();
            if ($country) {
                $country->code = $code;
                $country->save();
            }
        }

        return 'done';
    }
}

return new Admin;
