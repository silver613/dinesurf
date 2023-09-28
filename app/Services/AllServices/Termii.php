<?php

namespace App\Services\AllServices;

use App\Jobs\ProcessSms;
use GuzzleHttp\Client;

class Termii
{
    public static function sendSms(array $tos, $sms, $type = 'plain', $channel = 'generic')
    {
        $api_key = config('services.termii.key');
        $from = config('services.termii.sender_id');

        foreach ($tos as $key => $to) {
            $body = [
                'form_params' => [
                    'api_key' => $api_key, 'to' => $to, 'from' => $from, 'sms' => $sms, 'type' => $type, 'channel' => $channel,
                ],
            ];
            // ProcessSms::dispatch($body);
            $client = new Client();
            $client->request('POST', 'https://termii.com/api/sms/send', $body);
        }

        return 'message sent';
    }
}

return new Termii;
