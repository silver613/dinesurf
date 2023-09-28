<?php

namespace App\Services\AllServices;

use App\Models\Plan;
use App\Models\PlanFrequency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use Exception;
use Illuminate\Support\Str;

class TxnService
{
    public static function createTransaction($amount, $name, $phone, $email, $type, $currency, $payment_method, $client_secret, $vendor_id, $voucher_id = null, $status = null, $txn_ref = null, $plan_id = null, $frequency_id = null, $reservation_id = null, $paid_amount = 0, $balance = 0)
    {
        if ($type == 'subscription' && $vendor_id) {
            $vendor = Vendor::find($vendor_id);
            if ($vendor && $vendor->subscribed) {
                throw new Exception('You are already on an active Subscription Plan');
            }
        }

        if (! $txn_ref) {
            $txn_ref = Str::slug(config('app.name'), '-').'-'.Str::random().Str::slug(now(), '-');
        }

        $txn = new Transaction();
        // $txn->user_id = $user->id;
        $txn->plan_id = $plan_id;
        $txn->plan_frequency_id = $frequency_id;
        $txn->voucher_id = $voucher_id;
        $txn->vendor_id = $vendor_id;
        $txn->reservation_id = $reservation_id;
        $txn->amount = $amount;
        $txn->name = $name;
        $txn->phone = $phone;
        $txn->email = $email;
        $txn->reference = $txn_ref;
        $txn->currency = $currency;
        $txn->type = $type;
        $txn->payment_method = $payment_method;
        $txn->amount = $amount;
        $txn->paid_amount = $paid_amount;
        $txn->balance = $balance;
        $txn->client_secret = true;
        $txn->mode = config('services.paystack.mode');
        if ($status) {
            $txn->status = $status;
        }
        $txn->save();

        return $txn;
    }

    public static function updateTransactionStatus(string $transactionRef, string $status)
    {
        // Store Transaction
        $txn = Transaction::where('reference', $transactionRef)->first();

        if (! $txn) {
            return;
        }

        if ($status == 'success' && $txn->status != 'success' && $txn->type == 'subscription') {
            $plan = Plan::find($txn->plan_id);
            $plan_frequency = PlanFrequency::find($txn->plan_frequency_id);

            try {
                Admin::subscribe('vendor', $txn->vendor_id, $plan, $txn->reference, $plan_frequency);
            } catch (\Throwable $th) {
                throw $th;
            }

            session()->forget('billing_msg');
            session()->forget('billing_msg_status');
        }

        if ($status == 'failed' && $txn->type == 'subscription') {
            session(['billing_msg' => 'Your Subscription transaction failed, please try again.']);
            session(['billing_msg_status' => 'failed']);
        }

        if ($status != 'failed' && $status != 'abandoned' && $status != 'success' && $txn->type == 'subscription') {
            session(['billing_msg' => "Your Subscription transaction is ongoing. Once it's
            succesful, you'll be subscribed."]);
            session(['billing_msg_status' => 'success']);
        }

        $txn->status = $status;
        $txn->save();
    }
}

return new TxnService;
