<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Cuisine;
use App\Models\Day;
use App\Models\Review;
use App\Models\State;
use App\Models\Vendor;
use App\Services\AllServices\General;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $user = Auth::user();
        $party_size = $request->party_size;
        $query_text = strtolower($request->query('query'));
        $time = $request->time;
        $date = $request->date;
        $state_id = $request->state_id;
        $city_id = $request->city_id;
        $cuisine = Cuisine::find($request->cuisine);
        $price = $request->price;
        $category = $request->category;
        $lat = $request->lat;
        $lng = $request->lng;
        $take = $request->take;
        $path = '/search/restaurant';
        $type = $request->type;
        $rating = $request->rating;
        $stateQuery = null;

        $modelsQuery = Vendor::search($query_text)
            ->query(
                fn ($query) => $query->approved()->visible()->where('type', $type)
                    ->with(['cuisines', 'likes', 'days'])
                    // ->when($stateQuery, function ($query) use ($stateQuery) {
                    //     return $query->orwhere('state_id', $stateQuery);
                    // })
                    // ->when($state_id, function ($query) use ($state_id) {
                    //     return $query->where('state_id', $state_id);
                    // })

                    // ->when($city_id, function ($query) use ($city_id) {
                    //     return $query->where('city_id', $city_id);
                    // })
                    // ->when($party_size, function ($query) use ($party_size) {
                    //     return $query->where('party_size', '>=', $party_size);
                    // })

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
                    ->when($rating, function ($query) use ($rating) {
                        $vendors_ids = [];

                        $vendors_ids_with_reviews = Vendor::where('approved', true)->whereHas('reviews')->pluck('id')->toArray();
                        foreach ($vendors_ids_with_reviews as $key => $id) {
                            $ratings = Review::where('vendor_id', $id)->pluck('stars');
                            $total_ratings = Review::where('vendor_id', $id)->sum('stars');
                            $size = count($ratings);
                            $average = $size == 0 ? 0 : ($total_ratings / $size);

                            if ($average >= $rating && $average < ($rating + 1)) {
                                array_push($vendors_ids, $id);
                            }
                        }

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

                        if ($category == 'popular') {
                            return $query->with('reservations')
                                ->withCount('reservations');
                        }
                    })

                    ->when($price, function ($query) use ($price) {
                        if ($price == 1) {
                            return $query->where('average_menu_price', '<=', 10000);
                        }
                        if ($price == 2) {
                            return $query->where('average_menu_price', '>', 10000)->where('average_menu_price', '<=', 25000);
                        }
                        if ($price == 3) {
                            return $query->where('average_menu_price', '>', 25000)->where('average_menu_price', '<=', 40000);
                        }
                        if ($price == 4) {
                            return $query->where('average_menu_price', '>', 40000);
                        }
                    })
                    ->when($lat, function ($query) use ($lat) {
                        return $query->whereBetween('latitude', [$lat, $lat - (1 * 0.018)]);
                    })
                    ->when($lng, function ($query) use ($lng) {
                        return $query->whereBetween('longitude', [$lng, $lng - (1 * 0.018)]);
                    })
                    ->when($take, function ($query) use ($take) {
                        return $query->take($take);
                    })
                    ->when($category == 'popular', function ($query) {
                        return $query->orderBy('reservations_count', 'desc');
                    })
            );

        if ($take) {
            $models = $modelsQuery->get()->each->setAppends(['average_menu_price_nairas',  'hours', 'banner_url', 'average_rating',  'liked']);
        } else {
            $models = $modelsQuery->paginate()->withQueryString();

            if ($path) {
                $models->withPath($path);
            }
        }

        $history = General::getUserSearchHistory($user);

        return response()->json([
            'models' => $models,
            'history' => $history,
        ]);
    }

    public function getState($id)
    {
        $state = State::findOrFail($id);

        return response()->json($state);
    }

    public function getCity($id)
    {
        $city = City::findOrFail($id);

        return response()->json($city);
    }
}
