<?php

namespace App\Observers;

use App\Mail\Welcome;
use App\Models\User;
use App\Models\UserRegistrationLog;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Mail::to($user->email)->queue(new Welcome($user));
        // $dateTime = now()->startOfDay()->toDateTimeString();
        // $log = UserRegistrationLog::where('created_at', '>=', $dateTime)->first();
        // if ($log) {
        //     $log->total = $log->total + 1;
        //     $log->save();
        // } else {
        //     $newlog = new UserRegistrationLog;
        //     $newlog->total = 1;
        //     $newlog->save();
        // }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
