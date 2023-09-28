<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Resources\Voucher as ResourcesVoucher;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\Voucher;
use App\Models\VoucherUsageLog;
use App\Services\AllServices\Admin;
use App\Services\AllServices\TxnService;
use App\Services\PaymentMethods\Paystack;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function freeTrial(Request $request)
    {
        $vendor = session('current_vendor');
        $voucher = Voucher::find($request->voucher_id);
        $exists = VoucherUsageLog::where('vendor_id', $vendor->id)->where('voucher_id', $voucher->id)->where('used', true)->first();

        if ($exists) {
            return Helper::apiRes("You've Already Used a Free Trial Voucher'", [], false, 401);
        }

        if (! $voucher->duration_type) {
            return Helper::apiRes("Free Trial Voucher Not Valid'", [], false, 401);
        }

        if ($voucher->duration_type == 'no_of_days' && ! $voucher->duration) {
            return Helper::apiRes("Free Trial Voucher Not Valid'", [], false, 401);
        }

        if ($voucher->duration_type == 'until_fixed_date' && ! $voucher->discount_until) {
            return Helper::apiRes("Free Trial Voucher Not Valid'", [], false, 401);
        }

        try {
            Admin::applyVoucher($request->voucher_id, 'vendor', 'free_trial', true, $vendor);
        } catch (\Throwable $th) {
            throw $th;
        }

        $vendor->free_trial = true;
        $vendor->free_trial_start = now()->toDateTimeString();
        if ($voucher->duration_type == 'until_fixed_date') {
            $vendor->free_trial_end = Carbon::parse($voucher->discount_until)->toDateString();
        }
        if ($voucher->duration_type == 'no_of_days') {
            $vendor->free_trial_end = now()->addDays($voucher->duration)->toDateString();
        }
        $vendor->save();

        return Helper::apiRes('Free Trial Enabled');
    }

    public function startTransaction(Request $request)
    {
        $request->validate([
            'vendor_id' => 'integer|exists:vendors,id',
            'plan_id' => 'integer|exists:plans,id|nullable',
        ]);

        try {
            $txn = TxnService::createTransaction($request->amount, $request->name, $request->phone, $request->email, $request->txn_type, $request->currency, $request->paymentMethod, $request->client_secret, $request->vendor_id, $request->voucher_id, null, null, $request->plan_id, $request->frequency_id, $request->reservation_id);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false);
        }

        return Helper::apiRes('Transaction Initialized', $txn);
    }

    public function paystackVerify($txn_id)
    {
        $vendor = session('current_vendor');

        try {
            $res = Paystack::verify($txn_id);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }

        try {
            Admin::storeAuthCode('vendor_id', $vendor->id, $res);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }

        TxnService::updateTransactionStatus($txn_id, $res->data->status);
        // TxnService::updateTransactionStatus($txn_id, "pending");

        return Helper::apiRes('Transaction Verified');
    }

    public function paystackVerifyEvent($txn_id)
    {
        try {
            $res = Paystack::verify($txn_id);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false);
        }

        return Helper::apiRes('Transaction Verified', $res);
    }

    public function makePayment(Request $request)
    {
        $request->validate([
            'voucher_id' => 'integer|exists:vouchers,id',
            'plan_id' => 'integer|exists:plans,id',
            'reference' => 'string|exists:transactions,reference',
            'type' => [Rule::in(['vendor', 'user'])],
            'payment_type' => [Rule::in(['subscription', 'new_card'])],
        ]);

        if ($request->payment_type == 'new_card') {
            try {
                Paystack::refund($request->reference);
            } catch (\Throwable $th) {
                throw $th;
            }

            return redirect()->route('vendors.billing')->with(['success' => 'Card Updated Succesfully']);
        }

        if ($request->payment_type == 'subscription' && $request->type == 'vendor') {
            if ($request->voucher_id) {
                try {
                    Admin::applyVoucher($request->voucher_id, $request->type, $request->payment_type, true);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        }

        $vendor = session('current_vendor');
        $vendor = Vendor::find($vendor->id);
        session(['current_vendor' => $vendor]);

        return redirect()->route('vendors.billing')->with(['success' => 'Subscription Succesful']);
    }

    public function addCard($txn_id)
    {
        $user = Auth::user();

        try {
            $res = Paystack::verify($txn_id);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false);
        }

        TxnService::updateTransactionStatus($txn_id, $res->data->status);

        if ($res->data->status == 'success') {
            try {
                Paystack::refund($txn_id);
            } catch (\Throwable $th) {
                return Helper::apiRes($th->getMessage(), [], false);
            }

            try {
                $card = Admin::storeAuthCode('user_id', $user->id, $res);
            } catch (\Throwable $th) {
                return Helper::apiRes($th->getMessage(), [], false);
            }

            if ($card) {
                $res->card = $card;
            }
        }

        return Helper::apiRes('Transaction Verified', $res);
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:vouchers,code',
            'type' => 'required|in:vendor,user',
        ]);

        try {
            $voucher = Admin::checkVoucher($request->code, $request->type);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 422);
        }

        return Helper::apiRes('Voucher valid!', new ResourcesVoucher($voucher));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'type' => 'required|in:vendor,user',
            'usage_type' => 'required|in:subscription,one_time',
            'use' => 'required|boolean',
        ]);

        try {
            Admin::applyVoucher($request->voucher_id, $request->type, $request->usage_type, $request->use);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 422);
        }

        $msg = 'Voucher Applied!';

        if ($request->type == 'vendor') {
            $msg = 'Voucher applied and will take effect from next Billing Cycle';
        }

        return Helper::apiRes($msg);
    }

    public function paystackCallback(Request $request)
    {
        try {
            $event = Paystack::verifyEvent($request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 500);
        }

        if ($event) {
            if ($event->data) {
                try {
                    if ($event->data->reference && $event->data->status) {
                        $ref = $event->data->reference;
                        $status = $event->data->status;

                        TxnService::updateTransactionStatus($ref, $event->data->status);

                        if ($event->data->authorization) {
                            $card = null;
                            $vendor_id = Transaction::where('reference', $ref)->value('vendor_id');

                            try {
                                $card = Admin::storeAuthCode('vendor_id', $vendor_id, $event);
                            } catch (\Throwable $th) {
                                // throw new Exception($th);
                                Log::error($th);
                            }

                            if ($card) {
                                Log::info("\nCard with card_id $card->id for vendor_id $vendor_id was created and stored\n");
                            }
                        }

                        return Helper::apiRes("Transaction with reference: $ref Updated  it's status to $status");
                    }
                } catch (\Throwable $th) {
                    return Helper::apiRes($th->getMessage(), [], false, 500);
                }
            }
        }

        return Helper::apiRes('verification passed but no update made');
    }
}
