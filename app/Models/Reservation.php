<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'seating_preferences' => 'array',
        'approved' => 'boolean',
        'created_by_vendor' => 'boolean',
    ];

    public $appends = ['hour_range', 'formatted_created_at', 'seating_prefs_array', 'invitation', 'is_invitation', 'iso_start', 'iso_end', 'past', 'edit', 'formated_date', 'formated_start_time', 'formated_end_time', 'formated_time'];

    public function getHourRangeAttribute()
    {
        $datetime = Carbon::parse($this->date.' '.$this->time);
        $date = new DateTime($datetime);
        $minutes = $date->format('i');
        if ($minutes > 0) {
            $date->modify('-'.$minutes.' minutes');
        }
        $h = Carbon::parse($date)->format('h');
        $h = (int) $h;

        $a = Carbon::parse($date)->format('a');
        $a = strtoupper($a);

        return $h.' '.$a;
    }

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
    }

    public function getSeatingPrefsArrayAttribute()
    {
        return explode(', ', $this->seating_preferences);
    }

    public function getIsoStartAttribute()
    {
        return $this->date.' '.$this->time;
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function getIsoEndAttribute()
    {
        return Carbon::parse($this->date.' '.$this->time)->addHour();
    }

    public function getIsInvitationAttribute()
    {
        $user = Auth::user();

        return $user ? $this->user_id != $user->id : null;
    }

    public function getPastAttribute()
    {
        return $this->date < now()->toDateString() || ($this->date == now()->toDateString() && $this->time < now()->format('H:i:s'));
    }

    public function getEditAttribute()
    {
        return false;
    }

    public function getInvitationAttribute()
    {
        $user = Auth::user();

        return $user ? Invitation::where('reservation_id', $this->id)->where('email', $user->email)->first() : null;
    }

    public function getFormatedTimeAttribute()
    {
        return Carbon::parse($this->time)->format('h:ia');
    }

    public function getFormatedStartTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('h:ia');
    }

    public function getFormatedEndTimeAttribute()
    {
        return Carbon::parse($this->end_time)->format('h:ia');
    }

    public function getFormatedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class)->where('status', 'success');
    }
}
