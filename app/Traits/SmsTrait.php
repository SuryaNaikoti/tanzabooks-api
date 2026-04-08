<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

trait SmsTrait
{

    public function __construct(
        User $user
    )
    {
        $this->user = $user;
    }

    protected function sendMobileOtp($mobile)
    {
        // Mocked logic - no external API called
    }

    protected function verifyMobileOtp($otp, $mobile)
    {
        $user = $this->user->where('mobile', $mobile)->first();
        
        if ($user) {
            // Update user mobile verification
            $user->update(['mobile_verified_at' => now()]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully (mock)',
            'data' => [
                'user' => $user ?? null,
                'token' => 'demo-token'
            ]
        ], 200);
    }
}
