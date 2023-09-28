<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Scout\Searchable;

class Vendor extends Authenticatable
{
    use HasFactory, HasProfilePhoto, Notifiable, Searchable, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'latest_deal', 'average_menu_price_nairas', 'menu_pdf_url', 'profile_photo_url', 'hours', 'banner_url', 'average_rating', 'number_of_reviews', 'liked', 'free_trial_starts', 'free_trial_ends',
    ];

    protected $casts = [
        'subscribed' => 'boolean',
        'attach_menu_pdf' => 'boolean',
        'verified' => 'boolean',
        'approved' => 'boolean',
        'open' => 'boolean',
        'featured' => 'boolean',
        'is_same_time' => 'boolean',
        'reservation_deposit_required' => 'boolean',
        'accept_reservation_deposit' => 'boolean',
        'free_trial_start' => 'date',
        'free_trial_end' => 'date',
        'min_reservation_deposit' => 'float',
    ];

    public function toSearchableArray()
    {
        $vendor = $this
            ->with('cuisines', 'features', 'events', 'deals')
            ->where('id', '=', $this->id)
            ->first()
            ->only('company_name', 'company_address', 'description', 'menu_highlights', 'cuisines', 'features', 'events', 'deals');

        return $vendor;
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Storage::cloud()->url($this->profile_photo_path) : url('images/favicon.jpg');
    }

    public function getAverageMenuPriceNairasAttribute()
    {
        if ($this->average_menu_price) {
            if ($this->average_menu_price <= 10000) {
                return '₦';
            }
            if ($this->average_menu_price > 10000 && $this->average_menu_price <= 25000) {
                return '₦₦';
            }
            if ($this->average_menu_price > 25000 && $this->average_menu_price <= 40000) {
                return '₦₦₦';
            }
            if ($this->average_menu_price > 40000) {
                return '₦₦₦₦';
            }
        } else {
            return '₦';
        }
    }

    public function getFreeTrialStartsAttribute()
    {
        return Carbon::parse($this->free_trial_start)->format('jS F Y');
    }

    public function getFreeTrialEndsAttribute()
    {
        return Carbon::parse($this->free_trial_end)->format('jS F Y');
    }

    public function getLikedAttribute()
    {
        $user = Auth::user();
        $like = null;
        if ($user) {
            $like = $this->likes->where('user_id', $user->id)->first();
        }

        return $like ? true : false;
    }

    public function getBannerUrlAttribute()
    {
        if ($this->banner == '' || $this->banner == null) {
            return url('images/restaurant.jpg');
        }

        return Storage::cloud()->url($this->banner);
    }

    public function getMenuPdfUrlAttribute()
    {
        if ($this->menu_pdf == '' || $this->menu_pdf == null) {
            return null;
        }

        return Storage::cloud()->url($this->menu_pdf);
    }

    public function getAverageRatingAttribute()
    {
        $ratings = Review::where('vendor_id', $this->id)->pluck('stars');
        $total_ratings = Review::where('vendor_id', $this->id)->sum('stars');
        $size = count($ratings);

        $average = $size == 0 ? 0 : ($total_ratings / $size);

        return round($average);
    }

    public function getNumberOfReviewsAttribute()
    {
        $ratings = Review::where('vendor_id', $this->id)->pluck('stars');
        $size = count($ratings);

        return $size;
    }

    public function getHoursAttribute()
    {
        if ($this->open_time && $this->close_time) {
            return Carbon::parse($this->open_time)->format('h:ia').' - '.Carbon::parse($this->close_time)->format('h:ia');
        } else {
            return '24/7';
        }
    }

    public function getLatestDealAttribute()
    {
        return Deal::where('vendor_id', $this->id)->where('active', true)->orderBy('id', 'DESC')->first();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function googleReviews()
    {
        return $this->hasMany(GoogleReview::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function searchLogs()
    {
        return $this->hasMany(SearchLog::class);
    }

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function blockLists()
    {
        return $this->hasMany(BlockList::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function card()
    {
        return $this->hasOne(Card::class);
    }

    public function cuisines()
    {
        return $this->belongsToMany(Cuisine::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class)->using(DayVendor::class)->withPivot(['open_time', 'close_time']);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class);
    }

    public function seatingPreferences()
    {
        return $this->hasMany(SeatingPreference::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return Team::find($this->id);
    }

    public function planFrequency()
    {
        return $this->belongsTo(PlanFrequency::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function voucherUsageLogs()
    {
        return $this->hasMany(VoucherUsageLog::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function eventReservations()
    {
        return $this->hasMany(EventReservation::class);
    }

    public function bank()
    {
        return $this->hasOne(Bank::class, 'sort_code', 'bank_code');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function menuSettings()
    {
        return $this->hasOne(MenuSetting::class);
    }
}
