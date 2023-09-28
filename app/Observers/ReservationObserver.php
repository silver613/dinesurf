<?php

namespace App\Observers;

use App\Jobs\ProcessBulkSms;
use App\Mail\SendMail;
use App\Models\Reservation;
use App\Services\AllServices\General;
use App\Services\AllServices\VendorEmails;
use Illuminate\Support\Facades\Mail;

class ReservationObserver
{
    /**
     * Handle the Reservation "created" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function created(Reservation $reservation)
    {
        // General::logGuest($reservation);
        $user = $reservation->user ? $reservation->user : $reservation->guest;
        $vendor = $reservation->vendor;

        if ($vendor->reservations->count() == 1) {
            try {
                VendorEmails::after_first_reservation($vendor);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        if ($user && $vendor && $reservation->status != 'approved') {
            if ($user->email && $reservation->created_by_vendor) {
                Mail::to($user->email)->queue(new SendMail($user->first_name, 'Reservation Created', "<p> Your Reservation with $vendor->company_name has been created and is awaiting approval.</p> <p>ID: $reservation->id </p> <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: pending </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", $vendor->attach_menu_pdf ? $vendor->menu_pdf : null, action_log: [
                    'vendor_id' => $vendor->id,
                    'user_id' => $user->id,
                    'route' => 'email',
                    'type' => 'reservation_email',
                    'action_by' => 'vendor',
                ]));
            }
            if (! $reservation->created_by_vendor) {
                Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, 'Reservation Created', "<p> $user->first_name $user->last_name just Created a Reservation with $vendor->company_name and is awaiting approval.</p> <p>ID: $reservation->id </p>  <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: pending </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('vendors.reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", action_log: [
                    'vendor_id' => $vendor->id,
                    'route' => 'email',
                    'type' => 'reservation_email',
                    'action_by' => 'vendor',
                ]));
            }

            $user_phone = $reservation->phone ? $reservation->phone : $user->phone_number;
            $user_phone = $user_phone ? $user_phone : $user->phone;

            if ($user_phone && $reservation->created_by_vendor) {
                // Text user
                $short_text = "Your Reservation with $vendor->company_name has been created and is awaiting approval.";
                ProcessBulkSms::dispatchAfterResponse($user_phone, $user->first_name, $short_text, 'Dinesurf', [
                    'user_id' => $user->id,
                    'route' => 'sms',
                    'type' => 'reservation_text',
                    'content' => $short_text,
                    'action_by' => 'vendor',
                    'vendor_id' => $vendor->id,
                ]);
            }

            if ($vendor->phone_number && ! $reservation->created_by_vendor) {
                // Text vendor
                $short_text = "$user->first_name $user->last_name just Created a Reservation with $vendor->company_name and is awaiting approval.";
                ProcessBulkSms::dispatchAfterResponse($vendor->phone_number, $vendor->company_name, $short_text, 'Dinesurf', [
                    'user_id' => $user->id,
                    'route' => 'sms',
                    'type' => 'reservation_text',
                    'content' => $short_text,
                    'action_by' => 'vendor',
                    'vendor_id' => $vendor->id,
                ]);
                if ($vendor->company_phone && $vendor->company_phone != $vendor->phone_number) {
                    ProcessBulkSms::dispatchAfterResponse($vendor->company_phone, $vendor->company_name, $short_text, 'Dinesurf', [
                        'user_id' => $user->id,
                        'route' => 'sms',
                        'type' => 'reservation_text',
                        'content' => $short_text,
                        'action_by' => 'vendor',
                        'vendor_id' => $vendor->id,
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Reservation "updated" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function updated(Reservation $reservation)
    {
        $user = $reservation->user ? $reservation->user : $reservation->guest;
        $vendor = $reservation->vendor;

        if ($user->email && $reservation->created_by_vendor) {
            Mail::to($user->email)->queue(new SendMail($user->first_name, 'Reservation Updated', "<p> Your Reservation with $vendor->company_name has been modified.</p> <p>ID: $reservation->id </p> <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: pending </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", action_log: [
                'vendor_id' => $vendor->id,
                'route' => 'email',
                'type' => 'reservation_email',
                'action_by' => 'vendor',
            ]));
        }
        if (! $reservation->created_by_vendor) {
            Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, 'Reservation Modified', "<p> $user->first_name $user->last_name just modified a Reservation with $vendor->company_name.</p> <p>ID: $reservation->id </p> <p>Date: $reservation->formated_date </p>  <p>Time: $reservation->formated_time </p> <p>Status: pending </p> <p>Login to view Reservation.</p> <div class='reservation-div'> <a href='".route('vendors.reservation', ['id' => $reservation->id])."' class='btn reservation-btn'>View Reservation</a> </div>", action_log: [
                'vendor_id' => $vendor->id,
                'route' => 'email',
                'type' => 'reservation_email',
                'action_by' => 'vendor',
            ]));
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function deleted(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the Reservation "restored" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function restored(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the Reservation "force deleted" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function forceDeleted(Reservation $reservation)
    {
        //
    }
}
