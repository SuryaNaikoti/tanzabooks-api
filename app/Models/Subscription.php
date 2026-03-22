<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'payment_id',
        'subscription_end',
        'subscription_start',
        'user_id',
        'subscription_plan_id',
    ];

    protected $casts = [
        'subscription_end' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
