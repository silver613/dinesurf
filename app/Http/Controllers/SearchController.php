<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Bank;
use App\Models\City;
use App\Models\Country;
use App\Models\Cuisine;
use App\Models\Day;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Services\AllServices\General;
use App\Services\PaymentMethods\Paystack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function searchVendor(Request $request)
    {
        $vendors = $this->mainSearch($request, 'restaurant', 5);

        return Helper::apiRes('Vendors', $vendors);
    }

    public function searchHistory()
    {
        return Helper::apiRes('History', General::getUserSearchHistory(auth()->user()));
    }

    public function searchVendorsPage(Request $request)
    {
        session(['query' => $request->query('query')]);
        session(['state_id' => $request->query('state_id')]);
        session(['city_id' => $request->query('city_id')]);

        $data = [
            'resource_name' => 'Search Restaurants',
            'query' => $request->query('query'),
            'cuisine' => $request->query('cuisine'),
            'party_size' => $request->query('party_size'),
            'date' => $request->query('date'),
            'time' => $request->query('time'),
            'state_id' => $request->query('state_id'),
            'city_id' => $request->query('city_id'),
            'price' => $request->query('price'),
            'category' => $request->query('category'),
            'lat' => $request->query('lat'),
            'lng' => $request->query('lng'),
            'defaultState' => true,
        ];

        return Inertia::render('Search/Vendors', $data);
    }

    public function mainSearch(Request $request, $type = null, $take = null)
    {
        $party_size = $request->query('party_size');
        $query = $request->query('query');
        $time = $request->query('time');
        $date = $request->query('date');
        $state_id = $request->query('state_id');
        $city_id = $request->query('city_id');
        $cuisine = Cuisine::find($request->query('cuisine'));
        $price = $request->query('price');
        $category = $request->query('category');
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $range = $this->getPriceRange($price);

        $split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $query, -1, PREG_SPLIT_NO_EMPTY);

        $vendors = Vendor::approved()
            ->where('type', $type)
            ->whereIn('company_name', $split)
            ->where('company_name', 'LIKE', '%'.$query.'%')
            ->when($state_id, function ($query) use ($state_id) {
                return $query->where('state_id', $state_id);
            })
            // ->when($city_id, function ($query) use ($city_id) {
            //     return $query->where('city_id', $city_id);
            // })
            ->when($party_size, function ($query) use ($party_size) {
                return $query->where('party_size', '>=', $party_size);
            })

            ->when($cuisine, function ($query) use ($cuisine) {
                $vendors_ids = $cuisine->vendors()->pluck('vendors.id')->toArray();

                return $query->whereIn('id', $vendors_ids);
            })
            ->when($date, function ($query) use ($date) {
                $day_name = Carbon::parse($date)->format('l');
                $day_name = strtolower($day_name);
                $day = Day::where('name', $day_name)->first();

                $vendors_ids = $day->vendors()->pluck('vendors.id')->toArray();

                return $query->whereIn('id', $vendors_ids);
            })
            ->when($time, function ($query) use ($time) {
                return $query->where('open_time', '>=', $time)->where('close_time', '<', $time);
            })
            ->when($take, function ($query) use ($take) {
                return $query->take($take);
            })

            ->when($category, function ($query) use ($category) {
                if ($category == 'feature') {
                    return $query->where('featured', true);
                }
            })

            ->when($price, function ($query) {
                return $query->whereBetween('price', [$range['from'], $range['to']]);
            })

            // ->when($lat, function ($query) use ($lat) {
            //     return $query->whereBetween('latitude', [$lat, $lat - (1 * 0.018)]);
            // })
            // ->when($lng, function ($query) use ($lng) {
            //     return $query->whereBetween('longitude', [$lng, $lng - (1 * 0.018)]);
            // })

            ->with(['cuisines', 'likes'])
            ->get()
            ->sortBy(function ($vendor) {
                $category = $request->query('category');
                if ($category == 'popular') {
                    return $vendor->reservations->count();
                }
            });

        return $vendors;
    }

    public function searchUser(Request $request)
    {
        $query = $request->query('query');

        if (! $query || $query == '') {
            return Helper::apiRes('Users', []);
        }

        $users = User::where('name', 'LIKE', '%'.$query.'%')
            ->orWhere('email', 'LIKE', '%'.$query.'%')
            ->take(15)
            ->get();

        return Helper::apiRes('Users', $users);
    }

    public function countries(Request $request)
    {
        $models = Country::all();

        return Helper::apiRes('Models', $models);
    }

    public function states(Request $request)
    {
        $country_id = $request->country_id;
        $with_vendors = $request->with_vendors;

        $models = State::when($country_id, function ($query) use ($country_id) {
            return $query->where('country_id', $country_id);
        })
         ->when($with_vendors, function ($query) {
             return $query->whereHas('vendors', function ($q) {
                 $q->where('approved', true);
             });
         })
            ->get();

        return Helper::apiRes('Models', $models);
    }

    public function cities(Request $request)
    {
        $state_id = $request->state_id;

        $models = City::when($state_id, function ($query) use ($state_id) {
            return $query->where('state_id', $state_id);
        })
            ->get();

        return Helper::apiRes('Models', $models);
    }

    public function banks()
    {
        $models = Bank::all();

        return Helper::apiRes('Models', $models);
    }

    public function verifyAccount(Request $request)
    {
        $res = Paystack::verifyAccountNumber($request->account_number, $request->bank_code);

        return Helper::apiRes('Models', $res);
    }

    public function getPriceRange($id)
    {
        $data = [];

        if ($id == 1) {
            $data['from'] = 0;
            $data['to'] = 10999;
        } elseif ($id == 2) {
            $data['from'] = 11000;
            $data['to'] = 14999;
        } elseif ($id == 3) {
            $data['from'] = 15000;
            $data['to'] = 24999;
        } elseif ($id == 4) {
            $data['from'] = 25000;
            $data['to'] = 1000000;
        }

        return  $data;
    }
}
