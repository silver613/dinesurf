<?php

namespace App\Observers;

use App\Mail\VendorWelcome;
use App\Models\Team;
use App\Models\Vendor;
use App\Services\AllServices\Admin;
use App\Services\AllServices\Mira;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VendorObserver
{
    /**
     * Handle the Vendor "created" event.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function created(Vendor $vendor)
    {
        try {
            Admin::createVendorTeam($vendor);
        } catch (\Throwable $th) {
            throw $th;
        }

        Mail::to($vendor->email)->queue(new VendorWelcome($vendor));
        $vendor->slug = Str::slug($vendor->company_name, '-');
        $vendor->saveQuietly();
    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function updated(Vendor $vendor)
    {
        $vendor->slug = Str::slug($vendor->company_name, '-');
        $vendor->saveQuietly();

        if ($vendor->mira_id) {
            try {
                Mira::update($vendor);
            } catch (\Throwable $th) {
                //throw $th;
                Log::error($th);
            }

            if ($vendor->isDirty('bank_code') || $vendor->isDirty('account_number')) {
                Mira::bankAccount($vendor);
            }
        }
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function deleted(Vendor $vendor)
    {
        Team::where('id', $vendor->id)->delete();
    }

    /**
     * Handle the Vendor "restored" event.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function restored(Vendor $vendor)
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function forceDeleted(Vendor $vendor)
    {
        //
    }
}
