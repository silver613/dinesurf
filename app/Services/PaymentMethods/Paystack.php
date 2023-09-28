<?php

namespace App\Services\PaymentMethods;

use App\Services\AllServices\TxnService;
use Exception;
use Illuminate\Support\Facades\Log;

class Paystack
{
    private static $secret_key;

    public function __construct()
    {
        if (config('services.paystack.mode') == 'live') {
            self::$secret_key = config('services.paystack.live_sk');
        } else {
            self::$secret_key = config('services.paystack.test_sk');
        }
    }

    public static function charge($amount, $email, $authorization_code)
    {
        $url = 'https://api.paystack.co/transaction/charge_authorization';
        $fields = [
            'authorization_code' => $authorization_code,
            'email' => $email,
            'amount' => $amount * 100,
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.self::$secret_key,
            'Cache-Control: no-cache',
        ]);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function refund($txn_reference)
    {
        $url = 'https://api.paystack.co/refund';
        $fields = [
            'transaction' => $txn_reference,
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.self::$secret_key,
            'Cache-Control: no-cache',
        ]);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function fetchTransfer($transfer_code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paystack.co/transfer/'.$transfer_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.self::$secret_key,
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function fetchTransaction($txn_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paystack.co/transaction/'.$txn_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.self::$secret_key,
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function verifyEvent($request)
    {
        Log::info('handling paystack event');
        if (! $request->isMethod('post') || ! $request->hasHeader('x-paystack-signature')) {
            Log::error('paystack event UnAuthorized 1');
            throw new Exception('UnAuthorized 1', 401);
        }

        // Retrieve the request's body
        // $input = $request->input();
        // $input = json_encode($input);
        $input = $request->getContent();
        Log::info('input: '.$input);
        Log::info('signature1: '.$request->header('x-paystack-signature'));
        Log::info('signature2: '.hash_hmac('sha512', $input, self::$secret_key));
        // validate event do all at once to avoid timing attack
        if ($request->header('x-paystack-signature') !== hash_hmac('sha512', $input, self::$secret_key)) {
            Log::error('paystack event UnAuthorized 2');
            throw new Exception('UnAuthorized 2', 401);
        }

        // parse event (which is json string) as object
        // Do something - that will not take long - with $event
        $event = json_decode($input);
        Log::info('done handling paystack event');

        return $event;
    }

    public static function transfer(float $amount, $account_number, $bank_code, $account_name, $currency = 'NGN')
    {
        $recipientObject = null;
        $transfer = null;

        try {
            $recipientObject = self::createTransferRecipient($account_number, $bank_code, $account_name, $currency);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }

        if ($recipientObject && $recipientObject->data->recipient_code) {
            try {
                $transfer = self::initiateTransfer($amount, $recipientObject->data->recipient_code);
            } catch (\Throwable $th) {
                throw new Exception($th->getMessage());
            }
        }

        return $transfer;
    }

    public static function initiateTransfer(float $amount, string $recipient)
    {
        $url = 'https://api.paystack.co/transfer';
        $fields = [
            'source' => 'balance',
            'amount' => $amount * 100,
            'recipient' => $recipient,
            'reason' => 'Transfer',
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.self::$secret_key,
            'Cache-Control: no-cache',
        ]);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            self::initiateTransfer($amount, $recipient);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function createTransferRecipient(string $acc_no, string $bank_code, string $name, string $currency)
    {
        $url = 'https://api.paystack.co/transferrecipient';
        $fields = [
            'type' => 'nuban',
            'name' => $name,
            'account_number' => $acc_no,
            'bank_code' => $bank_code,
            'currency' => $currency,
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.self::$secret_key,
            'Cache-Control: no-cache',
        ]);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function createSubAccount(string $company_name, string $bank_code, string $acc_no, float $percentage = 1)
    {
        $url = 'https://api.paystack.co/subaccount';

        $fields = [
            'business_name' => $company_name,
            'bank_code' => $bank_code,
            'account_number' => $acc_no,
            'percentage_charge' => $percentage,
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.self::$secret_key,
            'Cache-Control: no-cache',
        ]);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function verifyAccountNumber(string $acc_no, string $bank_code)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number={$acc_no}&bank_code=".$bank_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.self::$secret_key,
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception('INCORRECT_ACCOUNT_DETAILS '.$err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function verify($trans_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paystack.co/transaction/verify/'.$trans_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.self::$secret_key,
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function listBanks()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paystack.co/bank',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.self::$secret_key,
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function balance()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paystack.co/balance',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.self::$secret_key,
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        }

        $response = json_decode($response);
        if (! $response->status) {
            throw new Exception($response->message);
        }

        return $response;
    }

    public static function handleWebHook($payload)
    {
        // Handle the event
        switch ($payload->event) {
            case 'charge.success':
                TxnService::updateTransactionStatus($payload->data->reference, $payload->data->status);
                break;
                // ... handle other event types
            default:
                echo 'Received unknown event type '.$payload->event;
        }
    }
}

return new Paystack;
