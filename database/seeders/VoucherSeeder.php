<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
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
                'code' => 'KIM26th',
                'name' => "Kim oprah's voucher",
                'max_uses' => 1,
                'type' => 'percentage',
                'discount_amount' => 10,
                'quantity_required' => 2,
            ],

            [
                'code' => 'FREEDOM',
                'name' => 'Free Trial voucher',
                'type' => 'percentage',
                'discount_amount' => 100,
                'duration' => 90,
                'duration_type' => 'no_of_days',
            ],

            [
                'code' => 'FREE227',
                'name' => 'Free Trial voucher',
                'type' => 'percentage',
                'discount_amount' => 100,
                'duration' => 90,
                'duration_type' => 'no_of_days',
            ],

            [
                'code' => 'Provy26th',
                'name' => "Provy oprah's voucher",
                'type' => 'price',
                'discount_amount' => 30,
            ],
        ];

        foreach ($models as $key => $value) {
            $model = new Voucher;
            $model->name = $value['name'];
            $model->code = $value['code'];
            $model->type = $value['type'];
            $model->discount_amount = $value['discount_amount'];
            if (isset($value['duration_type'])) {
                $model->duration_type = $value['duration_type'];
            }
            if (isset($value['duration'])) {
                $model->duration = $value['duration'];
            }
            if (isset($value['max_uses'])) {
                $model->max_uses = $value['max_uses'];
            }
            if (isset($value['quantity_required'])) {
                $model->quantity_required = $value['quantity_required'];
            }
            $model->save();
        }
    }
}
