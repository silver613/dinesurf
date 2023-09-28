<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Vendor;
use App\Services\AllServices\TxnService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            // Account
            'account_id' => 'required|exists:vendors,mira_id',
        ]);

        $vendor = Vendor::where('mira_id', $request->account_id)->firstOrFail();

        $request->validate([
            // Transaction
            'transaction.amount' => 'required|numeric|min:0',
            'transaction.paid_amount' => 'required|numeric|min:0',
            'transaction.balance' => 'required|numeric|min:0',
            'transaction.currency' => 'required|string|in:NGN,USD',
            'transaction.status' => 'required|string',
            'transaction.reference' => 'required|string|unique:transactions,reference',

            // Order
            'id' => [
                Rule::unique('orders', 'mira_id')->where('vendor_id', $vendor->id),
            ],
            'amount' => 'required|numeric|min:0',
            'name' => 'required|string',
            'phone' => 'string',
            'email' => 'required|string|email',
            'status' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'notes' => 'required|string',
            'table_number' => 'required',

            // order item
            'items*.name' => 'required|string',
            'items*.price' => 'required|numeric|min:0',
            'items*.quantity' => 'required|numeric|min:0',
        ]);

        $transaction = $request->transaction;
        $items = $request->items;

        $txn = TxnService::createTransaction(amount: $transaction['amount'], name: $request->name, phone: $request->phone, email: $request->email, type: 'order', currency: $transaction['currency'], payment_method: 'mira', client_secret: null, vendor_id: $vendor->id, status: $transaction['status'], txn_ref: $transaction['reference'], paid_amount: $transaction['paid_amount'], balance: $transaction['balance']);
        $clientId = $request->header('X-Client-ID');
        $client = Client::whereId($clientId)->value('name');

        $orderData = $request->only([
            'table_number',
            'amount',
            'status',
            'name',
            'email',
            'rating',
            'vendor_id',
            'notes',
        ]);

        $orderData['transaction_id'] = $txn->id;
        $orderData["{$client}_id"] = $request->id;
        $orderData['vendor_id'] = $vendor->id;

        $order = Order::create($orderData);

        $items = collect($request->items)->map(function ($item) use ($order) {
            $item['order_id'] = $order->id;

            return $item;
        });

        $order->items()->createMany($items);

        return Helper::apiRes('order created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // Account
            'account_id' => 'required|exists:vendors,mira_id',

            // Transaction
            'transaction.amount' => 'required|numeric|min:0',
            'transaction.paid_amount' => 'required|numeric|min:0',
            'transaction.balance' => 'required|numeric|min:0',
            'transaction.status' => 'required|string',

            // Order
            'amount' => 'required|numeric|min:0',
            'name' => 'required|string',
            'phone' => 'string',
            'email' => 'required|string|email',
            'status' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'notes' => 'required|string',
            'table_number' => 'required',

            // order item
            'items*.name' => 'required|string',
            'items*.price' => 'required|numeric|min:0',
            'items*.quantity' => 'required|numeric|min:0',
        ]);

        $clientId = $request->header('X-Client-ID');
        $client = Client::whereId($clientId)->value('name');


        $order = Order::where("{$client}_id", $id)->with('transaction', 'items')->first();

        if (! $order) {
            Helper::apiRes('order not found', [], false, 404);
        }

        $order->update($request->except(['transaction', 'items', 'id', 'account_id']));

        $order->transaction->update($request->transaction);

        $order->items()->delete();

        $items = collect($request->items)->map(function ($item) use ($order) {
            $item['order_id'] = $order->id;

            return $item;
        });

        $order->items()->createMany($items);

        $order->load('items');

        return Helper::apiRes('order updated', $order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientId = request()->header('X-Client-ID');
        $client = Client::whereId($clientId)->value('name');

        Order::where("{$client}_id", $id)->firstOrFail()->delete();

        return Helper::apiRes('order deleted');
    }
}
