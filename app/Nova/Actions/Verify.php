<?php

namespace App\Nova\Actions;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Verify extends Action
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
        foreach ($models as $key => $model) {
            $model->verified = true;
            $model->save();

            Mail::to($model->email)->queue(new SendMail($model->company_name, 'Account Verified', 'Your Dinesurf Vendor Account has been verified.', action_log: [
                'route' => 'email',
                'type' => 'verify_email',
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
        return [];
    }
}
