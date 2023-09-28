<?php

namespace App\Services\AllServices;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BulkSmsNg
{
    public static function sendSms($tos, string $from, $sms, $user_firstname = null)
    {
        $api_key = config('services.bulk_sms_ng.api_key');

        try {
            $body = [
                'form_params' => [
                    'api_token' => $api_key,
                    'to' => $tos,
                    'from' => $from,
                    'body' => $user_firstname ? 'Hello '.$user_firstname.', '.$sms : 'Hello, '.$sms,
                    'gateway' => '0',
                    'append_sender' => '0',
                ],
            ];
            $client = new Client();
            $response = $client->request('POST', 'https://www.bulksmsnigeria.com/api/v1/sms/create', $body);
            $body = $response->getBody();
            $body = json_encode($body);

            Log::info($body);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
        }

        Log::info('message sent');

        return 'message sent';
    }
}

return new BulkSmsNg;
