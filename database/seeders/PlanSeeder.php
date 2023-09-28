<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\PlanFrequency;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            [
                'name' => 'Plan 1',
            ],
            [
                'name' => 'Plan 2',
            ],
        ];

        foreach ($models as $key => $value) {
            $model = new Plan();
            $model->name = $value['name'];
            $model->save();

            $feature = new PlanFeature;
            $feature->name = 'Manage Reservations';
            $feature->plan_id = $model->id;
            $feature->save();

            $feature = new PlanFeature;
            $feature->name = 'Verification Eligibility';
            $feature->plan_id = $model->id;
            $feature->save();

            $feature = new PlanFeature;
            $feature->name = 'Reservation Frequency Metrics';
            $feature->plan_id = $model->id;
            $feature->save();

            $feature = new PlanFeature;
            $feature->name = 'Send Email and Text Messages to Guests';
            $feature->plan_id = $model->id;
            $feature->save();

            $frequency = new PlanFrequency();
            $frequency->plan_id = $model->id;
            $frequency->price = 100;
            $frequency->duration = 14;
            $frequency->duration_text = '2 weeks';

            $frequency->save();

            $frequency = new PlanFrequency();
            $frequency->plan_id = $model->id;
            $frequency->price = 180;
            $frequency->duration = 30;
            $frequency->duration_text = 'monthly';
            $frequency->save();

            $frequency = new PlanFrequency();
            $frequency->plan_id = $model->id;
            $frequency->price = 900;
            $frequency->duration = 365;
            $frequency->duration_text = 'yearly';
            $frequency->save();

            // $feature = new PlanFeature;
            // $feature->name = "Push To Deploy";
            // $feature->plan_id = $model->id;
            // $feature->save();
        }
    }
}
