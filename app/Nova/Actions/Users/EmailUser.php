<?php

namespace App\Nova\Actions\Users;

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

class EmailUser extends Action
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
        foreach ($models as $key => $user) {
            Mail::to($user->email)->queue(new SendMail($user->first_name, $fields->subject, "<div>$fields->message</div>", action_log: [
                'user_id' => $user->id,
                'route' => 'email',
                'type' => 'email_user',
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
            // Text::make('Link Users Should Click', 'link'),

        ];
    }
}
