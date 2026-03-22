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

    protected function sendMobileOtp(int $mobile)
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create('+91' . $mobile, "sms");
    }

    protected function verifyMobileOtp(int $otp, int $mobile)
    {
        $user = $this->user->where('mobile', $mobile)->firstOrFail();

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);

        try {
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create([
                    'to' => '+91' . $mobile,
                    "code" => $otp
                ]);
            if ($verification->valid) {
                //update user mobile verification
                $user->update(['mobile_verified_at' => now()]);

                //generate password reset token
                $token = Str::random(60);
                $this->passwordReset->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                return api_response($token, true, 200, 'Password reset token generated!');
            }
        } catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
        return api_response(null, false, 500, 'OTP Do not match!');
    }
}
