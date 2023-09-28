<?php

namespace App\Services\AllServices;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TripAdvisor
{
    public static function getReviews($id = '23238587')
    {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://api.content.tripadvisor.com/api/v1/location/$id/reviews?language=en&key=".config('services.trip_advisor.api_key'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error($th);
            $msg = $th->getMessage();
            print_r("\nan Error occured for trip Advisor id: $id:\n $msg \n");
        }

        $data = isset($response) ? json_decode($response->getBody()) : null;

        return $data;
    }

    public static function getImages($id = '23238587')
    {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://api.content.tripadvisor.com/api/v1/location/$id/images?language=en&key=".config('services.trip_advisor.api_key'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error($th);
            $msg = $th->getMessage();
            print_r("\nan Error occured for trip Advisor id: $id:\n $msg \n");
        }

        $data = isset($response) ? json_decode($response->getBody()) : null;

        return $data;
    }
}

return new TripAdvisor;
