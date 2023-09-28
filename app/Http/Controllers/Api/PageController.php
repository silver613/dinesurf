<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuisine;
use App\Models\Event;
use App\Models\Vendor;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function restaurants(Request $request)
    {
        $category = $request->category;
        $lat = $request->lat;
        $lng = $request->lng;
        $take = $request->take;
        $deals = $request->deals;
        $state_id = $request->state_id;

        $cuisine = $request->cuisine;
        $city = $request->city;

        $path = 'restaurants';

        $query = Vendor::approved()->visible();
        $query = $query->where('type', 'restaurant')->with(['cuisines', 'days',  'likes'])
            ->when($category == 'popular', function ($query) {
                return $query->with('reservations')
                    ->withCount('reservations');
            })
            ->when($category == 'featured', function ($query) {
                return $query->where('featured', true)->inRandomOrder();
            })
            ->when($category == 'near' || $category == 'all', function ($query) use ($state_id) {
                // return $query->when(is_numeric($lat), function ($query) use ($lat) {
                //     return $query->orWhereBetween('latitude', [$lat, $lat - (1 * 0.018)]);
                // })
                //     ->when(is_numeric($lng), function ($query) use ($lng) {
                //         return $query->orWhereBetween('longitude', [$lng, $lng - (1 * 0.018)]);
                //     })
                //     ->inRandomOrder();
                return $query->where('state_id', $state_id);
            })

            ->when($category == 'new', function ($query) {
                return $query->orderBy('id', 'desc');
            })
            ->when($deals, function ($query) {
                return $query->whereHas('deals');
            })
            ->when($take, function ($query) use ($take) {
                return $query->take($take);
            })
            ->when($category == 'popular', function ($query) {
                return $query->orderBy('reservations_count', 'desc');
            })

            // newly added

            ->when($city, function ($query) use ($city) {
                if ($city == 'lagos') {
                    return $query->where('company_address', 'LIKE', '%'.$city.'%')
                        ->orWhere('company_address', 'LIKE', '%Ikeja%');
                }

                return $query->where('company_address', 'LIKE', '%'.$city.'%');
            })

            ->when($cuisine, function ($query) use ($cuisine) {
                $cuisines = Cuisine::where('name', $cuisine)->first();
                if ($cuisines) {
                    $vendors_ids = $cuisines->vendors()->pluck('vendors.id')->toArray();

                    return $query->whereIn('id', $vendors_ids);
                }
            });

        if ($take) {
            $models = $query->get()->each->setAppends(['average_menu_price_nairas',  'hours', 'banner_url', 'average_rating',  'liked']);
        } else {
            $models = $query->paginate()->withQueryString();

            if ($path) {
                $models->withPath($path);
            }
        }

        return response()->json([
            'models' => $models,
        ]);
    }

    public function events()
    {
        $query = Event::query()->with(['vendor', 'images', 'reservations']);
        $query->orderBy('end_date', 'desc');
        $models = $query->get();

        return response()->json([
            'models' => $models,
        ]);
    }

    // public function Allevents()
    // {
    //     $query = Event::query()->with('vendor');
    //     $query->where('start_date', '>=', now()->toDateString());
    //     $query->where('start_date', '<=', now()->toDateString());
    //     $query->where('end_date', '>=', now()->toDateString());
    //     $query->orderBy('end_date', 'desc');
    //     $models = $query->paginate()->withQueryString();
    //     return response()->json([
    //         'models' => $models,
    //     ]);
    // }
}
