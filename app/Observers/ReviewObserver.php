<?php

namespace App\Observers;

use App\Jobs\ProcessBulkSms;
use App\Mail\SendMail;
use App\Models\Review;
use Illuminate\Support\Facades\Mail;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     *
     * @param  \App\Models\Review  $review
     * @return void
     */
    public function created(Review $review)
    {
        if ($review->vendor) {
            $vendor = $review->vendor;
            $msg = "<p> You've received a Customer's new review.</p>";
            $msg .= "<p> <span style='font-weight:700'>Customer: </span>  <span style='text-transform:capitalize'>$review->name</span>";
            $msg .= "<p> <span style='font-weight:700'>Ratings: </span> $review->stars stars";
            $msg .= $review->content ? "<p> <span style='font-weight:700'>Review: </span> $review->content" : '';
            $msg .= "<p>View your Reviews on your Dinesurf page.</p> <div class='reservation-div'> <a href='".route('restaurants.index', ['id' => $vendor->id])."?tab=reviews' class='btn reservation-btn'>View Reviews</a> </div>";

            Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, 'Review Created', $msg, action_log: [
                'vendor_id' => $vendor->id,
                'route' => 'email',
                'type' => 'review_email',
                'action_by' => 'vendor',
            ]));

            $short_text = "You've received a Customer's new review, check your dinesurf page to see reviews";
            ProcessBulkSms::dispatchAfterResponse($vendor->company_phone, $vendor->company_name, $short_text, 'Dinesurf', [
                'vendor_id' => $vendor->id,
                'route' => 'sms',
                'type' => 'review_text',
                'content' => $short_text,
                'action_by' => 'vendor',
            ]);
        }
    }

    /**
     * Handle the Review "updated" event.
     *
     * @param  \App\Models\Review  $review
     * @return void
     */
    public function updated(Review $review)
    {
        //
    }

    /**
     * Handle the Review "deleted" event.
     *
     * @param  \App\Models\Review  $review
     * @return void
     */
    public function deleted(Review $review)
    {
        //
    }

    /**
     * Handle the Review "restored" event.
     *
     * @param  \App\Models\Review  $review
     * @return void
     */
    public function restored(Review $review)
    {
        //
    }

    /**
     * Handle the Review "force deleted" event.
     *
     * @param  \App\Models\Review  $review
     * @return void
     */
    public function forceDeleted(Review $review)
    {
        //
    }
}
