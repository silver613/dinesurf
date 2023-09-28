<?php

namespace App\Nova\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChangeOwner extends Action
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
            $team = Team::find($model->id);
            $user = User::findOrFail($fields->user);

            $model->user_id = $user->id;
            $model->first_name = $user->first_name;
            $model->last_name = $user->last_name;
            $model->email = $user->email;
            $model->phone_number = $user->phone_number;
            $model->save();

            if ($team) {
                $team->user_id = $user->id;
                $team->save();
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $options = [];
        foreach (User::whereNotNull('first_name')->orderBy('first_name', 'asc')->get() as $key => $model) {
            $options[$model->id] = $model->first_name.' '.$model->last_name.' - '.$model->email;
        }

        return [
            Select::make('User')->searchable()->options($options),
        ];
    }
}
