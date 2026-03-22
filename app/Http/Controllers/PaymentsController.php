<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyPaymentRequest;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentsController extends Controller
{

    public function __construct()
    {

    }

    public function razorPayOrderGenerate(Request $request)
    {
        $data = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id'
        ]);

        if (isPlanActive())
            return api_response(null, false, 409, 'Active plan already exist');

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        if ($plan->price == 0){
            //create subscription plan
            $subscription = Subscription::create([
                'payment_id' => 1,
                'user_id' => Auth::id(),
                'subscription_plan_id' => $plan->id,
                'subscription_start' => now(),
                'subscription_end' => now()->addMonth(1)
            ]);

            return api_response($subscription, true, 200, 'FREE Subscription Added to your account for a month!');

        }

        $orderData = [
            'receipt' => 3456,
            'amount' => $plan->price * 100, // 2000 rupees in paisa
            'currency' => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        $razorpayOrder = $api->order->create($orderData);

//        dd($razorpayOrder);

        if ($razorpayOrder->status == 'created') {
            $responce['order_id'] = $razorpayOrder['id'];
            $responce['order_amount'] = $razorpayOrder['amount'];
            $responce['currency'] = $razorpayOrder['currency'];
            $responce['status'] = $razorpayOrder['status'];

            try {
                DB::beginTransaction();

                $payment = Payment::create([
                    'order_id' => $razorpayOrder->id,
                    'amount' => $plan->price,
                    'status' => $razorpayOrder->status,
                    'plan_id' => $plan->id,
                    'user_id' => Auth::id()
                ]);

                DB::commit();

                return api_response($payment);
            }
            catch (\Throwable $exception){
                DB::rollBack();
                return api_error($exception);
            }

        }

        return api_response(null, 500, false, 'Something went wrong!');

    }

    public function verifyPayment(VerifyPaymentRequest $request)
    {
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        try {

            $fetchPayment = $api->payment->fetch($request->razorpay_payment_id);

            if ($fetchPayment->status == 'captured'){
                $payment = Payment::where('order_id', $request->razorpay_order_id)->first();
                $payment->update([
                    'status' => $fetchPayment->status,
                    'payment_id' => $request->razorpay_payment_id
                ]);

                //create subscription plan
                $subscription = Subscription::create([
                    'payment_id' => $payment->id,
                    'user_id' => Auth::id(),
                    'subscription_plan_id' => $payment->plan_id,
                    'subscription_start' => now(),
                    'subscription_end' => now()->addMonth(1)
                ]);

                return api_response($subscription, true, 200, 'Subscription Added to your account!');
            }
            else{
                return api_response(null, false, 500, 'Payment Failed!');
            }

            // Please note that the razorpay order ID must
            // come from a trusted source (session here, but
            // could be database or something else)
//            $attributes = array(
//                'razorpay_order_id' => $request->razorpay_order_id,
//                'razorpay_payment_id' => $request->razorpay_payment_id,
//                'razorpay_signature' => $request->razorpay_signature
//            );
//
//            dd($api->utility->verifyPaymentSignature($attributes));
//
//            $payment = $api->utility->verifyPaymentSignature($attributes);
//
//            dd($payment);
//
//            $payment = $api->payment->fetch($request->payment_id);
//            dd($payment);
//            return api_response($payment);
        } catch (SignatureVerificationError $e) {
            return api_error($e);
        }

    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Payment $payment)
    {
        //
    }

    public function update(Request $request, Payment $payment)
    {
        //
    }

    public function destroy(Payment $payment)
    {
        //
    }
}
