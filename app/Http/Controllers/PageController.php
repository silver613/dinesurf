<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\AppSetting;
use App\Models\Day;
use App\Models\Dinelist;
use App\Models\Event;
use App\Models\Menu;
use App\Models\MenuSetting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPageViewCount;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use App\Models\Cuisine;
use App\Services\AllServices\General;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\DataMetric;
use Stevebauman\Location\Facades\Location;
  

class PageController extends Controller
{
    public function index()
    {
        session(['auth_type' => null]);
        $data = [
            'canLogin' => Route::has('login'),
            'appName' => config('app.name'),
            'canRegister' => Route::has('register'),
            'page' => 'index',
            'title' => 'Dining Reservations Made Easy - Dinesurf',
            'description' => 'Surf Restaurants in Lagos and Make Reservations',
        ];

        return Inertia::render('Index', $data);
    }

    public function deals()
    {
        $data = [
            'page' => 'deals',
            'title' => 'Restaurants With Deals',
            'description' => 'Browse restaurants under DINESURF that are current;y offering deals.',
        ];

        return Inertia::render('CampaignPages/Deals', $data);
    }
    // valentine

    public function valentine()
    {
        $data = [
            'page' => 'valentine',
            'title' => 'Valentine  Events',
            'description' => 'Browse events under DINESURF  that are happening on valentine day ',
        ];

        return Inertia::render('CampaignPages/Valentines', $data);
    }

    public function dashboard(Request $request)
    {
        return Inertia::render('Dashboard');
    }

    public function vendors(Request $request)
    {
        $category = $request->query('category');
        $city = $request->query('city');
        $cuisine = $request->query('cuisine');
        $state_id = $request->query('state_id');
        $deals = $request->query('deals');
        $take = $request->query('take');


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
            })->get()->toArray();


    // dd($query[0]['banner_url']);



        $name = 'Restaurants';
        $title = 'Restaurants - Dinesurf';
        $pageMetaDescription = "Best Dining in Lagos, Abuja and Rivers restaurants and search by cuisine, price, location, and more";
        $pageMetaKeywords = "restaurant, Lagos, Abuja, River,  Nigeria, reviews of restaurants, food, dining";
        $pageMetaUrl = \Request::fullUrl();
        $pageMetaImage = $query[0]['banner_url'];

        if ($city && ! $cuisine) {
            $name = 'Restaurants in '.$city;
            $title ='The '.count($query).' best restaurants in '.$city.' - Dinesurf';
            $pageMetaDescription = 'Best dining in '.$city.', see dinesurf diner reviews, '.count($query).' of '.$city.' restaurants and search by cuisine, price, location, and more'; 
            $pageMetaKeywords = "restaurant, ".$city.", Nigeria, reviews of restaurants, food, dining";
        }

        if ($city && $cuisine) {
            $name = $cuisine.' Food in '.$city;
            $title = 'The '.count($query).' best restaurants with '.$cuisine.' Food in '.$city.' - Dinesurf';
            $pageMetaDescription = 'Best dining in '.$city.' with '.$cuisine.', see dinesurf diner reviews, '.count($query).' of '.$city.' restaurants with '.$cuisine.' Food  and search by cuisine, price, location, and more'; 
            $pageMetaKeywords = "restaurant, ".$cuisine." food, ".$city.", Nigeria, reviews of restaurants, food, dining";
      
        }

        if ($category) {
            $name = $category.' Restaurants ';
            $title ='The '.count($query).' best and '.$category.' restaurants - Dinesurf';
            $pageMetaDescription = 'Best dining, '.$category.' restaurants, see dinesurf diner reviews, '.count($query).'  '.$category.' restaurants and search by cuisine, price, location, and more'; 
            $pageMetaKeywords = "restaurant, ".$category." reataurants, Nigeria, reviews of restaurants, food, dining";
    
        }

        if ($category == 'near') {
            $name = 'Restaurants near you';
            $title = 'See  '.count($query).' best restaurants around you - Dinesurf';
            $pageMetaDescription = 'Best dining restaurants, see dinesurf diner reviews, '.count($query).' restaurants around you and search by cuisine, price, location, and more'; 
            $pageMetaKeywords = "restaurant, reataurants near me, Nigeria, reviews of restaurants, food, dining";
    
        }

        $data = [
            'resource_name' => $name,
            'title' => $title,
            'description' => $pageMetaDescription,
            'keywords' => $pageMetaKeywords,
            'metaUrl' => $pageMetaUrl,
            'metaImage' => $pageMetaImage
        ];

        return Inertia::render('Vendor/Frontend/Home', $data);
    }

    public function getVendor(Request $request, $id)
    {
        $userIp = $request->ip();
        $this->vendorPageCount($userIp, $id);
        $user = Auth::user();
        $vendor = Vendor::approved()->visible()->where('id', $id)->with(['cuisines', 'country', 'state','city','reviews', 'likes', 'seatingPreferences'])->first();

        if (! $vendor) {
            abort(404);
        }

        if ($request->campaign_id) {
            $vendor->campaigns()->detach($request->campaign_id);
            $vendor->campaigns()->attach($request->campaign_id);
        }

        $searched = $request->query('searched');
        $searched_id = $request->query('searched_id');
        $vendor->load('seatingPreferences', 'days', 'features');

        if ($searched && $user) {
            General::logSearch($user, $searched, $searched_id);
        }

        $data = [
            'resource_name' => 'Restaurant',
            'vendor' => $vendor,
            'step' => $request->query('tab'),
        ];

        if ($vendor->banner) {
            $data['favicon'] = $vendor->profile_photo_url;
        }

        $menus = Menu::where('vendor_id', $id)->with(['menuCategories' => function ($query) {
            $query->with(['items']);
        }])->get();

        $events = Event::where('vendor_id', $id)->with(['vendor', 'images'])->get();
        $data['days'] = Day::all();

        $pageMetaTitle =$vendor->state?->name ? $vendor->company_name.', '.$vendor->state?->name.', '.$vendor->country?->name.' - Menu, Prices & Restaurant Reviews - Dinesurf' :  $vendor->company_name.', '.$vendor->country?->name.' - Menu, Prices & Restaurant Reviews - Dinesurf';
        $pageMetaDescription =$vendor->state?->name ? $vendor->company_name.', '.$vendor->state?->name.', '.$vendor->country?->name.', Explore Menu, Reviews, Photos, Location, Phone Number and make reservation for '.$vendor->company_name.', '.$vendor->country?->name. ' on Dinesurf' :  $vendor->company_name.', '.$vendor->country?->name.', Explore Menu, Reviews, Photos, Location, Phone Number and make reservation for '.$vendor->company_name.' on Dinesurf';
        $pageMetaKeywords =$vendor->state?->name ? $vendor->company_name.', '.$vendor->state?->name.', '.$vendor->country?->name.' restaurant, reviews of restaurants, food, dining'.$vendor->company_name. ' on Dinesurf' :  $vendor->company_name.', '.$vendor->country?->name.', restaurant, reviews of restaurants, food, dining'.$vendor->company_name.' on Dinesurf';
        $pageMetaUrl =route('restaurants', ['id' => $vendor->id]);
        // dd($vendor->country?->name);
        $data['menus'] = $menus;
        $data['events'] = $events;
        $data['paystackkey'] = config('services.paystack.mode') == 'live' ? config('services.paystack.live_pk') : config('services.paystack.test_pk');
        $data['settings'] = AppSetting::first();
        $data['title'] =$pageMetaTitle;
        $data['description'] =$pageMetaDescription;
        $data['keywords'] =$pageMetaKeywords;
        $data['metaUrl'] =$pageMetaUrl;
        $data['metaImage'] =$vendor->banner_url;

        return Inertia::render('Vendor/Frontend/Index', $data);
    }

    public function favouriteResturants()
    {
        $id = Auth::user()->id;

        $favourite_vendors = Vendor::approved()->visible()
        ->whereHas('likes', function (Builder $query) use ($id) {
            $query->where('user_id', $id);
        })
        ->where('type', 'restaurant')
        ->with('cuisines')->with('likes')->paginate(15);

        $current_user = User::find($id);
        $loggedInUser = Auth::user();

        $isLogged = Auth::user() ? true : false;
        $isSameUser = $isLogged && $loggedInUser->id == $id ? true : false;
        $list = Dinelist::with('vendors')->where('user_id', $id)->orderBy('id', 'Desc')->get();

        $favourite_dinelist = Dinelist::whereHas('likes', function (Builder $query) use ($id) {
            $query->where('user_id', $id);
        })->with(['likes', 'vendors'])->get();

        $data = [
            'resource_name' => 'DineList profile',
            'vendors' => $favourite_vendors,
            'favourite_dinelist' => $favourite_dinelist,
            'current_user' => $current_user,
            'isLogged' => $isLogged,
            'isSameUser' => $isSameUser,
            'list' => $list,
            'title' => 'Restaurants',
        ];
        $dinnerName = $current_user->displayname ? $current_user->displayname : $current_user->name;
        $pageMetaTitle = 'Browse '.count($list).' Curated Restaurants   list by '.$dinnerName.' - Dinelist by Dinesurf';
        $pageMetaDescription = "Explore restaurant recommendation made by ".$dinnerName.". See Restaurant Rankings, Community tips and Prices on Dinesurf";
        $pageMetaKeywords = $dinnerName.", dinesurf, restaurants, Lagos restaurants list, abuja restaurants list,  reviews of restaurants, food, dining";
        $pageMetaUrl =route('dinelist.profile', ['slug' => $current_user->slug]);
      
        $data['title'] =$pageMetaTitle;
        $data['description'] =$pageMetaDescription;
        $data['keywords'] =$pageMetaKeywords;
        $data['metaUrl'] =$pageMetaUrl;
        $data['metaImage'] =$current_user->image_url;

        return Inertia::render('Vendor/Frontend/Favourites', $data);
    }

    public function refreshToken(Request $request)
    {
        session()->regenerate();

        return response()->json(
            [
                'token' => csrf_token(),
            ],
            200
        );
    }

    public function back(Request $request)
    {
        $url = session('current_url');
        session()->forget('current_url');

        return redirect($url)->with(['success' => 'login successful']);
    }

    public function logCurrentUrl(Request $request)
    {
        session(['current_url' => $request->url]);

        return Helper::apiRes('Url logged');
    }

    public function reserveWithGoogleJSON(Request $request)
    {
        $vendors = Vendor::approved()->visible()->get();
        $merchants = [];

        foreach ($vendors as $key => $vendor) {
            if ($vendor->latitude && $vendor->longitude) {
                $phone = '';
                if ($vendor->phone_number) {
                    $phone = substr($vendor->phone_number, -10);
                    $phone = '+234'.$phone;
                }

                $data = [
                    'category' => 'restaurant',
                    'merchant_id' => "$vendor->id",
                    'name' => $vendor->company_name,
                    'url' => "app.dinesurf.com/restaurants/$vendor->id",
                    'telephone' => $phone,
                    'geo' => [
                        'latitude' => $vendor->latitude ? $vendor->latitude : '',
                        'longitude' => $vendor->longitude ? $vendor->longitude : '',
                        'address' => [
                            'locality' => $vendor->city ? $vendor->city->name : '',
                            'country' => 'NG',
                            'region' => $vendor->state ? $vendor->state->name : '',
                            'street_address' => $vendor->company_address,
                            'postal_code' => '',
                        ],
                    ],
                ];

                array_push($merchants, $data);
            }
        }

        $data = json_encode(
            [
                'metadata' => [
                    'processing_instruction' => 'PROCESS_AS_COMPLETE',
                    'shard_number' => 0,
                    'total_shards' => 1,
                    'nonce' => '11203880',
                    'generation_timestamp' => 1524606581,
                ],
                'merchant' => $merchants,
            ]
        );

        $fileName = 'merchants.json';
        $destinationPath = public_path().'/json/';
        if (! is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if (file_exists($destinationPath.$fileName)) {
            unlink($destinationPath.$fileName);
        }
        File::put($destinationPath.$fileName, $data);

        return response()->download($destinationPath.$fileName);
    }

    public function getPersonalMenu($slug)
    {
        $vendor = Vendor::approved()->visible()->where('slug', $slug)->orwhere('company_name', $slug)->first();

        if (! $vendor) {
            abort(404);
        }

        $menu_setting = MenuSetting::where('vendor_id', $vendor->id)->first();

        if ($menu_setting?->page == 'pdf-menu' && $menu_setting?->pdf != null) {
            return redirect()->route('menu.pdf', ['id' => $slug]);
        }

        $menus = Menu::where('vendor_id', $vendor->id)->with(['menuCategories' => function ($query) {
            $query->with(['items' => function ($query) {
                $query->where('status', 'on');
            }]);
        }])->orderBy('order', 'asc')->get();

        $data['vendor'] = $vendor;
        if ($vendor->banner) {
            $data['favicon'] = $vendor->profile_photo_url;
        }
        $data['menus'] = $menus;
        $data['setting'] = $menu_setting;

    //   $keywords =  "";
    //   $categories = array();
    //   if($menus){
    //     foreach($menus[0]?->menuCategories  as $category){
    //         if(count($categories) >  6) return false;
    //         array_push($categories, $category->name);
    //     }

    //     dd($categories);
    //     $keywords = count($categories) > 0 ? implode(',', $categories) : 'menu, price, food, restaurants - dinesurf menu';
    //  }

     $pageMetaTitle = $vendor->company_name.' - Menu List and Prices';
     $pageMetaKeywords = 'menu,menu items, menu category,  prices, food, restaurants - dinesurf menu';
     $pageMetaUrl =route('menu', ['id' => $vendor->slug]);
     $pageMetaDescription = "Explore ".$vendor->company_name." menu list and prices" ;
       
        $data['title'] =$pageMetaTitle;
        $data['keywords'] =$pageMetaKeywords;
        $data['metaUrl'] =$pageMetaUrl;
        $data['metaImage'] =$vendor->banner_url;
        $data['description'] =$pageMetaDescription;

        return Inertia::render('Menu', $data);
    }

    public function getPersonalMenuPdf($slug)
    {
        $vendor = Vendor::approved()->visible()->where('slug', $slug)->orwhere('company_name', $slug)->first();

        if (! $vendor) {
            abort(404);
        }

        $menu_setting = MenuSetting::where('vendor_id', $vendor->id)->first();

        if ($menu_setting?->page == 'dinesurf-menu') {
            return redirect()->route('menu', ['id' => $slug]);
        }

        $data['vendor'] = $vendor;
        if ($vendor->banner) {
            $data['favicon'] = $vendor->profile_photo_url;
        }
        $data['setting'] = $menu_setting;

        return Inertia::render('PDFMenu', $data);
    }

    public function getEvent($id)
    {
        $user = Auth::user();
        if ($user) {
            $event = Event::with(['images', 'reservations' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])->where('id', $id)->first();
        } else {
            $event = Event::where('id', $id)->first();
        }

        $vendor = Vendor::approved()->visible()->where('id', $event->vendor_id)->with(['cuisines', 'reviews', 'likes', 'seatingPreferences'])->first();
        if ($event->image_url) {
            $data['favicon'] = $event->image_url;
        }
        $data['reference'] = Str::slug(config('app.name'), '-').'-'.Str::random().Str::slug(now(), '-');
        $data['paystackkey'] = config('services.paystack.mode') == 'live' ? config('services.paystack.live_pk') : config('services.paystack.test_pk');
        $data['vendor'] = $vendor;
        $data['event'] = $event;

        return Inertia::render('Event', $data);
    }

    public function test()
    {
        $vendor = session('current_vendor');
    }

    public function reserve($slug)
    {
        $vendor = Vendor::where('slug', $slug)->where('approved', true)
            ->where('visible', true)
            ->with(['seatingPreferences', 'days'])
            ->firstOrFail()->setAppends(['profile_photo_url', 'hours', 'banner_url']);

        $data = [
            'page' => 'reserve',
            'title' => $vendor->company_name.'~ Make a Reservation',
            'description' => "Make a Reservation with $vendor->company_name",
            'vendor' => $vendor,
            'settings' => AppSetting::first(),
        ];

        $data['favicon'] = $vendor->profile_photo_url;
        $data['paystackkey'] = config('services.paystack.mode') == 'live' ? config('services.paystack.live_pk') : config('services.paystack.test_pk');

        return Inertia::render(
            'Vendor/Frontend/Reserve',
            $data
        );
    }

    public function allEvents()
    {
        $data['events'] = Event::all();

        return Inertia::render(
            'Allevents',
            $data
        );
    }

    public function vendorPageCount($userIp, $vendor_id)
    {
        $user = Auth::user();
        $user_id = $user ? $user->id : null;
        if($userIp === '127.0.0.1'){
            $location = null;
        }else{    
           $currentUserInfo = Location::get();
           $location = $currentUserInfo->cityName.', '.$currentUserInfo->regionName.', '.$currentUserInfo->countryName;
        }


        //  check if ip exists
        $checkUser = VendorPageViewCount::where('vendor_id', $vendor_id)->where('ip', $userIp)->first();

        if ($checkUser) {
            $checkUser->count = $checkUser->count + 1;
            $checkUser->ip = $checkUser->ip;
            $checkUser->vendor_id = $checkUser->vendor_id;
            $checkUser->location = $location;
            $checkUser->save();

            return;
        }

        $viewCount = new VendorPageViewCount;
        $viewCount->ip = $userIp;
        $viewCount->user_id = $user_id;
        $viewCount->vendor_id = $vendor_id;
        $viewCount->location = $location;
        $viewCount->count = 1;
        $viewCount->save();
    }

    public function dinelistProfile($slug)
    {
        $name = Str::title(str_replace('-', ' ', $slug));
        preg_match_all('/\d+/', $name, $matches);

        $id = $matches[0][0];
        $current_user = User::find($id);

        if (! $current_user) {
            abort(404, 'User not found');
            throw new Exception('User not found', 404);
        }

        $favourite_vendors = Vendor::approved()->visible()
        ->whereHas('likes', function (Builder $query) use ($id) {
            $query->where('user_id', $id);
        })
        ->where('type', 'restaurant')
        ->with('cuisines')->with('likes')->paginate(15);

        $loggedInUser = Auth::user();

        $isLogged = Auth::user() ? true : false;
        $isSameUser = $isLogged && $loggedInUser->id == $id ? true : false;

        if ($isSameUser) {
            $list = Dinelist::with(['vendors', 'likes'])->where('user_id', $id)->orderBy('id', 'Desc')->get();
        } else {
            $list = Dinelist::with(['vendors', 'likes'])->where('isPrivate', false)->where('user_id', $id)->orderBy('id', 'Desc')->get();
        }

        $favourite_dinelist = Dinelist::whereHas('likes', function (Builder $query) use ($id) {
            $query->where('user_id', $id);
        })->with(['likes', 'vendors'])->get();

        if (! $current_user) {
            abort(404, 'User not found');
            throw new Exception('User not found', 404);
        }

        $data = [
            'resource_name' => 'DineList profile',
            'vendors' => $favourite_vendors,
            'favourite_dinelist' => $favourite_dinelist,
            'current_user' => $current_user,
            'isLogged' => $isLogged,
            'isSameUser' => $isSameUser,
            'list' => $list,
            'title' => 'Restaurants',
        ];
        $dinnerName = $current_user->displayname ? $current_user->displayname : $current_user->name;
        $pageMetaTitle = 'Browse '.count($list).' Curated Restaurants   list by '.$dinnerName.' - Dinelist by Dinesurf';
        $pageMetaDescription = "Explore restaurant recommendation made by ".$dinnerName.". See Restaurant Rankings, Community tips and Prices on Dinesurf";
        $pageMetaKeywords = $dinnerName.", dinesurf, restaurants, Lagos restaurants list, abuja restaurants list,  reviews of restaurants, food, dining";
        $pageMetaUrl =route('dinelist.profile', ['slug' => $current_user->slug]);
      
        $data['title'] =$pageMetaTitle;
        $data['description'] =$pageMetaDescription;
        $data['keywords'] =$pageMetaKeywords;
        $data['metaUrl'] =$pageMetaUrl;
        $data['metaImage'] =$current_user->image_url;


        return Inertia::render('Vendor/Frontend/Favourites', $data);
    }


    public  function  PostPageMerics(Request $request){
       
        $userIp = $request->ip();
        $user = Auth::user();
        $user_id = $user ? $user->id : null;

        if($userIp === '127.0.0.1'){
            $location = null;
        }else{    
           $currentUserInfo = Location::get();
           $location = $currentUserInfo->cityName.', '.$currentUserInfo->regionName.', '.$currentUserInfo->countryName;
        }

        //  check if ip exists
        $checkUser = DataMetric::where('user_id', $user_id)->orWhere('ip', $userIp)->first();

        if ($checkUser) {
            $checkUser->count = $checkUser->count + 1;
            $checkUser->ip = $checkUser->ip;
            $checkUser->vendor_id = $checkUser->vendor_id;
            $checkUser->location = $location;
            $checkUser->save();

            return;
        }

        $viewCount = new DataMetric;
        $viewCount->ip = $userIp;
        $viewCount->user_id = $user_id;
        $viewCount->vendor_id = 0;
        $viewCount->location = $location;
        $viewCount->count = 1;
        $viewCount->metrics_name = 'home_button';
        $viewCount->save();
    }

    public function GetPageMerics($name){

      $data['data'] = DataMetric::where('metrics_name', $name)->get();
      return Inertia::render('ViewMetrics', $data);
    }
}
