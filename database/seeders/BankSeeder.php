<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Services\PaymentMethods\Paystack;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [];

        try {
            $response = Paystack::listBanks();
        } catch (\Throwable $th) {
            throw $th;
        }

        $banks = $response->data;

        foreach ($banks as $key => $bank) {
            $model = new Bank;
            $model->name = $bank->name;
            $model->sort_code = $bank->code;
            $model->save();
        }
    }
}
