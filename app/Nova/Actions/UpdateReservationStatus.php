<?php

namespace App\Nova\Actions;

use App\Services\AllServices\General;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpdateReservationStatus extends Action
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
            $model->status = $fields->status;
            $model->save();

            try {
                General::updateReservationStatus($model, $fields->status, 'admin');
            } catch (\Throwable $th) {
                throw $th;
            }
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
            Select::make('Status')->options([
                'pending' => 'pending',
                'approved' => 'approved',
                'cancelled' => 'cancelled',
            ]),
        ];
    }
}
