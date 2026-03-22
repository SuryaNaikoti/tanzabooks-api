<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlansController extends Controller
{
    public function __construct(SubscriptionPlan $subscriptionPlan)
    {
        $this->subscriptionPlan = $subscriptionPlan;
    }

    public function index()
    {
        return api_response($this->subscriptionPlan->all());
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        //
    }
}
