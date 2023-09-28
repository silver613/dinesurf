<?php

namespace App\Services\AllServices;

use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;

class TwilioService
{
    public static function sendSms($phone_number, $msg, $user_firstname = null)
    {
        $sid = config('services.twilio.account_sid');
        $token = config('services.twilio.auth_token');

        $twilio = new Client($sid, $token);

        try {
            $message = $twilio->messages
                ->create(
                    $phone_number, // to
                    [
                        'messagingServiceSid' => config('services.twilio.app_sid'),
                        'body' => 'Hello '.$user_firstname.', '.$msg,
                    ]
                );
        } catch (\Throwable $th) {
            // throw $th;
            Log::error($th);
        }

        print_r("\n mesage sent");
    }

    public static function replySms()
    {
        $response = new MessagingResponse();
        $response->message(
            "I'm using the Twilio PHP library to respond to this SMS!"
        );

        return $response;
    }

    public static function vendorResvationNotification(Reservation $reservation, $type)
    {
        $vendor = $reservation->vendor;
        $user = $reservation->user ? $reservation->user : $reservation->guest;
        $username = $user->first_name.' '.$user->last_name;
        $sid = config('services.twilio.account_sid');
        $token = config('services.twilio.auth_token');
        $twilio = new Client($sid, $token);

        if ($type == 'created') {
            // code...
            $body = "Hello $vendor->company_name, You have a reservation from $username set for $reservation->formated_date by $reservation->formated_time with a party size of $reservation->party_size. We recommend that you log into your Dinesurf account to approve this reservation. Cheers, The Dinesurf Team";
            // $default_body = "Hello {{1}}, You have a reservation from {{2}} set for {{3}} by {{4}} with a party size of {{5}}. We recommend that you log into your Dinesurf account to approve this reservation. Cheers, The Dinesurf Team";

            $twilio->messages
                ->create(
                    "whatsapp:$vendor->whatsapp_number", // to
                    [
                        'from' => 'whatsapp:'.config('services.twilio.number'),
                        'body' => $body,
                    ]
                );
        }

        return $body;
    }

    public static function vendorMetricsNotification(Reservation $reservation, $type)
    {
        $vendor = $reservation->vendor;
        $user = $reservation->user ? $reservation->user : $reservation->guest;
        $username = $user->first_name.' '.$user->last_name;
        $sid = config('services.twilio.account_sid');
        $token = config('services.twilio.auth_token');
        $twilio = new Client($sid, $token);

        if ($type == 'created') {
            // code...
            $body = "Hello {{1}}, you've had {{2}} page views this week, {{3}} reservations, {{4}} guest seated and Estimated revenue totaled {{5}}. Please log in to your dashboard to see your restaurant performance. Cheers, The Dinesurf Team.";
            // $default_body = "Hello {{1}}, you've had {{2}} page views this week, {{3}} reservations, {{4}} guest seated and Estimated revenue totaled {{5}}. Please log in to your dashboard to see your restaurant performance. Cheers, The Dinesurf Team.";

            $twilio->messages
                ->create(
                    "whatsapp:$vendor->whatsapp_number", // to
                    [
                        'from' => 'whatsapp:'.config('services.twilio.number'),
                        'body' => $body,
                    ]
                );
        }

        return $body;
    }

    public static function testWhatsapp()
    {
        $sid = config('services.twilio.account_sid');
        $token = config('services.twilio.auth_token');
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                'whatsapp:2348187655140', // to
                [
                    'from' => 'whatsapp:+14097465065',
                    'body' => 'Hello Burger King, You have a reservation from providence ifeosame set for march 3rd 2020 by 4pm with a party size of 10. We recommend that you log into your Dinesurf account to approve this reservation. Cheers, The Dinesurf Team',
                ]
            );

        echo $message->sid;
    }
}

return new TwilioService;
