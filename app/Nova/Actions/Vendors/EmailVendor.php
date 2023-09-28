<?php

namespace App\Nova\Actions\Vendors;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class EmailVendor extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $key => $vendor) {
            Mail::to($vendor->email)->queue(new SendMail($vendor->company_name, $fields->subject, $fields->message, action_log: [
                'route' => 'email',
                'type' => 'email_vendor',
                'action_by' => 'dinesurf',
            ]));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Subject')->rules('required'),
            Trix::make('Message')->rules('required'),
            // Text::make('Link Vendor(s) Should Click', 'link'),

        ];
    }
}
