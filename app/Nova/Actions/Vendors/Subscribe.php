<?php

namespace App\Nova\Actions\Vendors;

use App\Models\Plan;
use App\Models\PlanFrequency;
use App\Models\Subscription;
use App\Services\AllServices\Admin;
use App\Services\AllServices\TxnService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Subscribe extends Action
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
        $admin = auth()->user();
        if (! $admin->is_admin) {
            throw new Exception('You must be logged in with your admin account to do this, please logout and login with your admin account', 401);
        }

        foreach ($models as $key => $model) {
            $check = Subscription::where('vendor_id', $model->id)->where('active', true)->first();
            if ($check) {
                throw new Exception('Already Subscribed');
            }

            $plan = Plan::find($fields->plan);
            $planFrequency = PlanFrequency::find($fields->frequency);
            $user = $model->user;

            $txn = TxnService::createTransaction($planFrequency->price, $user->name, $user->phone, $user->email, 'subscription', $planFrequency->currency, 'cash', null, $model->id, null, 'success', null, $plan->id, $planFrequency->id);
            try {
                $sub = Admin::subscribe('vendor', $model->id, $plan, $txn->reference, $planFrequency);
            } catch (\Throwable $th) {
                throw $th;
            }

            $sub->subscribed_by = $admin->id;
            $sub->save();
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
        foreach (Plan::orderBy('name', 'asc')->get() as $key => $model) {
            $options[$model->id] = $model->name;
        }

        return [
            Select::make('Plan')->options($options)->rules('required'),
            Select::make('frequency')->dependsOn('plan', function (Select $field, NovaRequest $request, FormData $formData) {
                if ($formData->plan) {
                    $options = [];
                    foreach (PlanFrequency::where('plan_id', $formData->plan)->orderBy('duration_text', 'asc')->get() as $key => $model) {
                        $options[$model->id] = $model->duration_text.' - '.$model->currency.''.number_format($model->price, 2);
                    }
                    $field->searchable()->options($options)->show()->readonly(false)->rules(['required']);
                } else {
                    $field->hide()->readonly(true);
                }
            })->onlyOnForms(),
            Boolean::make('Do you confirm that this Vendor has paid cash for this subscription?', 'paid_cash')->rules('required', 'accepted'),
        ];
    }
}
