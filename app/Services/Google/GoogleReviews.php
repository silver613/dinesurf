<?php

namespace App\Services\Google;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleReviews
{
    public static function getReviews($id = '100')
    {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://greviews.dinesurf.tech/api/google/?format=json&pk=$id");
        } catch (\Throwable $th) {
            // throw $th;
            Log::error($th);
            $msg = $th->getMessage();
            print_r("\nan Error occured for Google Reviews id: $id:\n $msg \n");
        }

        $data = isset($response) ? json_decode($response->getBody()) : null;

        return $data;
    }
}

return new GoogleReviews;
