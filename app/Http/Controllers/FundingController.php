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
}
