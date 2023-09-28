<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\AllServices\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneralController extends Controller
{
    public function addMail(Request $request)
    {
        try {
            Admin::addToMailingList($request->email);
        } catch (\Throwable $th) {
            throw $th;
        }

        return Helper::apiRes('Mail Added', []);
    }

    public function maps()
    {
        $apiKey = config('services.google.api_key'); // Replace with your actual API key

        $url = "https://maps.googleapis.com/maps/api/js?key=$apiKey&libraries=places";

        $response = Http::get($url);

        $googleMapsScript = $response->body();

        return response($googleMapsScript)
        ->header('Content-Type', 'application/javascript');
    }
}
