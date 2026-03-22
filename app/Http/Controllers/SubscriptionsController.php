<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionsController extends Controller
{
    public function __construct(Subscription $subscription, SubscriptionPlan $subscriptionPlan)
    {
        $this->subscription = $subscription;
        $this->subscriptionPlan = $subscriptionPlan;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        dd($request);
        $plan = $this->subscriptionPlan->findOrFail($request->plan_id);

//        $userPlans = Auth::user()->subscription->count();
//
//        if (Auth::user()->subscription){
//
//        }

        try {
            DB::beginTransaction();

                $subscription = $this->subscription->create([
                    'user_id' => Auth::id(),
                    'subscription_plan_id' => $request->plan_id,
                    'subscription_start' => now(),
                    'subscription_end' => now()->addMonth(),
                    'payment_id' => 1,
                ]);


            DB::commit();

            return api_response($subscription, true, 201, 'Subscription Added!');
        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }



    }

    public function show(Subscription $subscription)
    {
        //
    }

    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    public function destroy(Subscription $subscription)
    {
        //
    }

    public function deleteActiveSubscription()
    {
        if (!env('APP_DEBUG'))
            return api_response(null, false, 403, 'UnAuthorized!');

        Subscription::where('user_id', Auth::id())->delete() ? $message = 'deleted' : $message = 'No Record found';

        return api_response($message);

    }
}
