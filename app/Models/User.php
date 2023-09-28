<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, HasTeams, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone_number',
        'birthday',
        'email',
        'source',
        'campaign_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'can_create_vendor' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url', 'image_url', 'slug',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Storage::cloud()->url($this->profile_photo_path) : url('images/favicon.jpg');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image == '' || $this->image == null) {
            return url('images/unknown_user.png');
        }

        $stored_image = Storage::disk('public')->exists($this->image);

        if ($stored_image) {
            return Storage::cloud()->url($this->image);
        }

        return Storage::cloud()->url($this->image);
    }

    public function getSlugAttribute()
    {
        $val = $this->displayname ? $this->displayname.'_'.$this->id : $this->name.'_'.$this->id;

        return Str::slug($val, '-');
    }

    public function dinelists()
    {
        return $this->hasMany(Dinelist::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userLogs()
    {
        return $this->hasMany(UserLog::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class);
    }

    public function searchLogs()
    {
        return $this->hasMany(SearchLog::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'email', 'email');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class);
    }

    public function blockLists()
    {
        return $this->hasMany(BlockList::class, 'email', 'email');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function planFrequency()
    {
        return $this->belongsTo(PlanFrequency::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function eventReservations()
    {
        return $this->hasMany(EventReservation::class);
    }

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function ownedTeams()
    {
        return $this->hasMany(Team::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }
}
