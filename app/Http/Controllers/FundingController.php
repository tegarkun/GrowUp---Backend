<?php

namespace App\Http\Controllers;

use App\Models\Funding;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FundingController extends Controller
{

    // selext funding for home page
    public function index() {

        $fundings = Funding::select("id","title", "image","target_amount","current_amount")->get();

        foreach ($fundings as $funding ) {

            $funding->percentange_succcess = ($funding['current_amount'] / $funding['target_amount']) * 100;
        }

        // $fundings->percentage_progress = ($fundings['current_amount'] / $fundings['target_amount']) * 100;


        return response()->json($fundings);

    }

    public function detail($id) {
            $funding = Funding::where('id',$id)->first();

            $funding->percentange_succcess = ($funding->current_amount / $funding->target_amount) * 100;
            return response()->json($funding);



    }

    public  function fundManual(Request $request){
        $validator = Validator::make($request->all(), [
            'amount'      => 'required',
            'funding_id'      => 'required',

        ]);

        //Check validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $funding = Funding::where('id' , $request->funding_id)->first();

        $funding->current_amount = $funding->current_amount + $request->amount;

        $funding->update();


        return response()->json([
            'status' => true
        ]);

    }


    public function Fund(Request $request) {

        $validator = Validator::make($request->all(), [
            'amount'      => 'required',
            'funding_id'      => 'required',

        ]);


        //Check validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        /// Get Midtrans
        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('IS3DS');

        $user = auth()->user();


        $user = User::find($user->id);
        $orderId = 'O-' . time(); // Generate a unique order ID

        // Prepare transaction details
        $transaction_details = array(
            'order_id' => $orderId,
            'gross_amount' => $request->amount,
            // Add other transaction details as needed (e.g., currency)
        );


        $items = array(
            array(
                'id' => 'item-' . time(),
                'price' => $request->amount,
                'quantity' => 1,
                'name' => "Funding", // Provide the order item name dynamically
            ),
            // Add more items if required
        );

        $name = explode(' ', $user['name']);
        $customer_details = array(
            'first_name' => $name[0],
            'email' => $user['email'],
            'phone' => "085125125", // Replace this with the actual phone number
        );

        $transaction = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $items,
        );



        $snapShot = \Midtrans\Snap::createTransaction($transaction);




        $payment = Payment::create([
            "service_name" => "Snapshot",
            "payment_code" => $snapShot->redirect_url,
            'service_id' => $snapShot->token,
            'payment_url' => $snapShot->redirect_url,
            'user_id' => $user->id,
            'status' => false,
            "amount" => $request->amount
        ]);

        Transaction::create(['user_id' => $user->id,
        'payment_id' => $payment->id,
        'status' => false,
        'funding_id' => $request->funding_id
         ]);

        return response()->json($snapShot);



    }

    public function webHookHandler(Request $request) {
    // Retrieve the data from the webhook payload
    $data = $request->all();

    // Extract relevant data
    $signatureKey = $data['signature_key'];
    $orderId = $data['order_id'];
    $statusCode = $data['status_code'];
    $grossAmount = $data['gross_amount'];
    $serverKey = env('MIDTRANS_SERVER_KEY');

    // Generate your signature key
    $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    // Check if the signature keys match
    if ($signatureKey !== $mySignatureKey) {
        return response()->json([
            'message' => 'Invalid signature',
            'data' => $data,
        ], 400);
    }

    // Find the corresponding payment in your database
    $payment = Payment::where('payment_code', $orderId)->first();

    if (!$payment) {
        return response()->json([
            'message' => 'Payment not found',
            'data' => $data,
        ], 400);
    }

    // Check if the payment has already been processed
    if ($payment->status === 1) {
        return response()->json([
            'message' => 'Payment already processed',
            'data' => $data,
        ], 400);
    }

    // Check the transaction status from Midtrans
    $transactionStatus = $data['transaction_status'];
    $fraudStatus = $data['fraud_status'];

    // Process the transaction status accordingly
    if ($transactionStatus == 'capture') {
        if ($fraudStatus == 'challenge') {
            // TODO: Set transaction status on your database to 'challenge'
            $payment->status = 3;
            // And respond with 200 OK
        } else if ($fraudStatus == 'accept') {
            // TODO: Set transaction status on your database to 'success'
            // And respond with 200 OK
            $payment->status = 1;
        }
    } else if ($transactionStatus == 'settlement') {
        // TODO: Set transaction status on your database to 'success'
        $payment->status = 1;

        // Handle funding process
        $transaction = Transaction::where('payment_id', $payment->id)->first();
        $funding = Funding::find($transaction->funding_id);

        // Update funding's current amount
        $funding->current_amount = $funding->current_amount + $transaction->amount;
        if ($funding->current_amount >= $funding->target_amount) {
            $funding->status = 1;
        }
        $funding->save();

        // Update payment status
        $payment->save();

        // Update transaction status
        $transaction->status = true;
        $transaction->save();
    } else if (
        $transactionStatus == 'cancel' ||
        $transactionStatus == 'deny' ||
        $transactionStatus == 'expire'
    );

}




}
